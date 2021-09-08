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

class SchedulerOperation extends SchedulerAbstractOperation
{
    private $forbiddenDays = [];
    private $schedulePreparing = [];
    private $scheduleExams;

    /**
     * SchedulerOperation constructor.
     */
    public function __construct()
    {
        $this->scheduleExams = $this->toFormScheduleExams();
    }

    /**
     * @throws Exception
     */
    public function createSchedule()
    {
        Schedule::deleteAll(['type_id' => ScheduleTypes::TYPE_PREPARING]);
        foreach ($this->scheduleExams as $exam => $schedule) {
            $interestDay = new DateTime(key($schedule));
            $this->forbiddenDays[] = $interestDay->format('Y-m-d');
            $this->reservePreparingDay($exam, $schedule, $interestDay);
        }

        $this->saveSchedulePreparing();
    }

    /**
     * Получить расписание подготовки с сортировкой
     * @var int $sort
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
     * @return array
     */
    private function toFormScheduleExams()
    {
        $scheduleExams = Schedule::find()->with(['exam'])->where(['type_id' => ScheduleTypes::TYPE_EXAM])->all();
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
     * @var array $schedule
     * @var DateTime|null $interestDay
     * @return bool
     * @throws Exception
     */
    private function reservePreparingDay($exam, $schedule, DateTime $interestDay = null)
    {
        $interestDay = $interestDay ?? new DateTime(key($schedule));
        for ($i = 1; $i <= $schedule[key($schedule)]; $i++) {
            //Если дата свободная, то резервируем на этот день подготовку к экзамену
            if (!in_array($interestDay->modify('-1 days')->format('Y-m-d'), $this->forbiddenDays)) {
                $this->forbiddenDays[] = $interestDay->format('Y-m-d');
                $this->schedulePreparing[$exam] = [$interestDay->format('Y-m-d') => $schedule[key($schedule)]];
                return true;
                //Если дата занята, то пробуем освободить этот день, чтобы поместить новый
            } else {
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
