<?php

namespace app\components\operations;

use app\components\entity\Exams;
use app\components\entity\Schedule;
use app\components\entity\ScheduleTypes;
use app\components\operations\abstractOperations\SchedulerAbstractOperation;
use DateTime;
use Exception;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class SchedulerOperation
 * Планировщик
 * Формирует расписание дней подготовки на основании расписания экзаменов по следующему алгоритму:
 *  1) Занести день проведения экзамена в массив с недоступными днями
 *  2) Проверить свободен ли день
 *  3) Если свободен, то переходим к шагу 6
 *  4) Если занят, то проверяем, день подготовки ли это
 *  5) Если день подготовки, то пробуем перенести день подготовки на другой день, чтобы освободить текущий
 *      (возвращаемся к шагу 2)
 *  6) Резервируем день в массиве расписания дней подготовки
 *  7) Удаляем старое расписание с типом Подготовка и сохраняем в таблицу новое
 *
 * Для формирования расписания сначала следует инициализировать планировщик. Затем с помощью метода createSchedule
 * произвести формирование расписания. Каждый вызов метода createSchedule стирает прошлое расписание и формирует новое,
 * исходя из записей в таблице schedule (где type_id = 1, то есть тип расписания экзамен).
 *
 * @package app\components\operations
 */
class SchedulerOperation extends SchedulerAbstractOperation
{
    /**
     * Недоступные дни
     * @var array
     */
    private $forbiddenDays = [];
    /**
     * Расписание дней подготовки
     * @var array
     */
    private $schedulePreparing = [];
    /**
     * Расписание дней проведения экзаменов
     * @var array
     */
    private $scheduleExams;

    /**
     * SchedulerOperation constructor.
     */
    public function __construct()
    {
        // Получаем все дни для проведения экзаменов в формате:
        // [ "Название экзамена" => ["Дата проведения экзамена" => "Количество дней для подготовки"]]
        $this->scheduleExams = $this->toFormScheduleExams();
    }

    /**
     * Сформировать расписание дней для подготовки
     * @throws Exception
     */
    public function createSchedule()
    {
        // Из таблицы с расписанием удаляем все дни с типом расписания "Подготовка"
        Schedule::deleteAll(['type_id' => ScheduleTypes::TYPE_PREPARING]);
        // Для каждого экзамена проводим попытку зарезервировать день для подготовки
        foreach ($this->scheduleExams as $exam => $schedule) {
            // Интересующий день (день проведения экзамена)
            $interestDay = new DateTime(key($schedule));
            // Добавляем интересующий день (день проведения экзамена) в массив с недоступными днями
            $this->forbiddenDays[] = $interestDay->format('Y-m-d');
            // Проводим попытку зарезервировать день для подготовки
            $this->reservePreparingDay($exam, $schedule, $interestDay);
        }

        $this->saveSchedulePreparing();
    }

    /**
     * Получить расписание подготовки с сортировкой
     * @var int $sort Метод сортировки (принимает константу)
     * @return array
     */
    public function getSchedulePreparing($sort = SORT_DESC)
    {
        ArrayHelper::multisort($this->schedulePreparing, function ($schedule) {
            return key($schedule);
        }, $sort);
        return $this->schedulePreparing;
    }

    /**
     * Сформировать массив с расписанием экзаменов и количеством дней для подготовки
     * @return array [ string => [date => int] ]
     */
    private function toFormScheduleExams()
    {
        //Получаем
        $scheduleExams = Schedule::find()
            ->with(['exam'])
            ->where(['type_id' => ScheduleTypes::TYPE_EXAM])
            ->orderBy(['date' => SORT_ASC])
            ->all();
        return ArrayHelper::map($scheduleExams, function (Schedule $schedule) {
            return $schedule->exam->name;
        }, function (Schedule $schedule) {
            return [$schedule->date => $schedule->exam->days];
        });
    }

    /**
     * @throws \yii\db\Exception
     */
    private function saveSchedulePreparing()
    {
        $transaction = Yii::$app->db->beginTransaction();
        foreach ($this->schedulePreparing as $examName => $schedule) {
            $exam = Exams::findOne(['name' => $examName]);
            $schedulePreparing = new Schedule();
            $schedulePreparing->type_id = ScheduleTypes::TYPE_PREPARING;
            $schedulePreparing->exam_id = $exam->id;
            $schedulePreparing->date = date('Y-m-d', strtotime(key($schedule)));
            $schedulePreparing->save();
        }
        $transaction->commit();
    }

    /**
     * Зарезервировать день подготовки
     * Возвращает true, если удалось зарезервировать
     * @var string $exam Название экзамена
     * @var array $schedule Расписание экзамена
     * @var DateTime|null $interestDay Интересующий день
     * @return bool
     * @throws Exception
     */
    private function reservePreparingDay($exam, $schedule, DateTime $interestDay = null)
    {
        // Если null, то это попытка перенести день подготовки к экзамену
        $interestDay = $interestDay ?? new DateTime(key($schedule));
        // Ищем ближайшую дату для подготовки к экзамену. Если дата занята, то пробуем освободить этот день
        for ($i = 1; $i <= $schedule[key($schedule)]; $i++) {
            //Если дата свободная, то резервируем на этот день подготовку к экзамену
            $interestDay->modify('-1 days');
            if (!in_array($interestDay->format('Y-m-d'), $this->forbiddenDays)) {
                $this->forbiddenDays[] = $interestDay->format('Y-m-d');
                $this->schedulePreparing[$exam] = [$interestDay->format('Y-m-d') => $schedule[key($schedule)]];
                return true;
            } else {
                //Если дата занята, то пробуем освободить этот день, чтобы поместить новый
                $days = ArrayHelper::getColumn($this->schedulePreparing, $interestDay->format('Y-m-d'));
                if ($exam !== key($days) && !is_null($days[key($days)])) {
                    //Если в этот день готовимся к другому экзамену, то пробуем перенести день подготовки подальше
                    $reserveSchedule = [$interestDay->format('Y-m-d') => $days[key($days)]];
                    if ($this->reservePreparingDay(key($days), $reserveSchedule)) {
                        $this->forbiddenDays[] = $interestDay->format('Y-m-d');
                        $this->schedulePreparing[$exam] = [$interestDay->format('Y-m-d') => $schedule[key($schedule)]];
                        return true;
                    }
                }
                //Если в эту дату проводится экзамен, то смотрим следующий день
                continue;
            }
        }

        return false;
    }
}
