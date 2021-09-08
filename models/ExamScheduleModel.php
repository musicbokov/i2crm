<?php

namespace app\models;

use app\components\entity\Exams;
use app\components\entity\ExamsSchedule;
use app\components\entity\ExceptionsCode;
use app\components\entity\Schedule;
use app\components\entity\ScheduleTypes;
use app\components\exceptions\ScheduleExceptions;
use Yii;
use yii\base\Model;
use yii\db\Exception;

/**
 * Class ExamScheduleModel
 * Модель для работы с расписанием
 * @property string $examName Название экзамена
 * @property string $examDate Дата проведения экзамена
 * @property int $preparingDays Количество дней для подготовки
 * @package app\models
 */
class ExamScheduleModel extends Model
{
    /**
     * @var string
     */
    public $examName;
    /**
     * @var string
     */
    public $examDate;
    /**
     * @var int
     */
    public $preparingDays;

    /**
     * @return string[]
     */
    public function attributeLabels()
    {
        return [
            'examName' => 'Название экзамена',
            'examDate' => 'Дата проведения экзамена',
            'preparingDays' => 'Количество дней для подготовки'
        ];
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [[
                'examName',
                'examDate',
                'preparingDays'
            ], 'required', 'message' => 'Все поля обязательны для заполнения'],
            [
                'examName',
                'string',
                'max' => 10,
                'message' => 'Слишком длинное название для экзамена'
            ],
            [
                'examName',
                'match',
                'pattern' => '/^[A-Za-z]+$/',
                'message' => 'Наименование экзамена может состоять только из латинских букв'
            ],
            ['examDate', 'checkDate'],
            [
                'preparingDays',
                'integer',
                'min' => 1,
                'message' => 'Количество дней не может быть меньше 1'
            ],
        ];
    }

    /**
     * @param string $attribute Наименование атрибута
     */
    public function checkDate($attribute)
    {
        if ($this->$attribute !== date('Y-m-d', strtotime($this->$attribute))) {
            $this->addError($attribute, 'Дата не соответствует формату');
        }
    }

    /**
     * Сохраняет
     * @throws ScheduleExceptions|Exception
     */
    public function saveExamsWithSchedule()
    {
        $transaction = Yii::$app->db->beginTransaction();
        $exam = $this->saveExams();
        $this->saveExamsSchedule($exam);
        $transaction->commit();
    }

    /**
     * Сохраняет экзамен в системе
     * @return Exams|null
     * @throws ScheduleExceptions
     */
    private function saveExams()
    {
        if (Exams::findOne(['name' => $this->examName])) {
            $ex = ExceptionsCode::findOne(['code' => ExceptionsCode::CODE_NAME_EXIST]);
            throw new ScheduleExceptions($ex->name, $ex->code);
        } else {
            $exam = new Exams();
            $exam->name = $this->examName;
            $exam->days = $this->preparingDays;
            $exam->save();
            return $exam;
        }
    }

    /**
     * Сохраняет расписание для экзамена
     * @var Exams $exam Идентификатор экзамена
     * @throws ScheduleExceptions
     */
    private function saveExamsSchedule(Exams $exam)
    {
        if(
            $examSchedule = Schedule::findOne([
                'date' => date('Y-m-d', strtotime($this->examDate)),
                'type_id' => ScheduleTypes::TYPE_EXAM
            ])
        ) {
            $ex = ExceptionsCode::findOne(['code' => ExceptionsCode::CODE_DATE_EXIST]);
            throw new ScheduleExceptions(
                str_replace(
                    ['[дата]', '[экзамен]'],
                    [date('d.m.Y', strtotime($examSchedule->date)), $examSchedule->exam->name],
                    $ex->name
                ),
                $ex->code
            );
        } else {
            $examSchedule = new Schedule();
            $examSchedule->type_id = ScheduleTypes::TYPE_EXAM;
            $examSchedule->exam_id = $exam->id;
            $examSchedule->date = $this->examDate;
            $examSchedule->save();
        }
    }
}
