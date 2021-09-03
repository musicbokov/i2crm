<?php

namespace app\components\entity;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Class ScheduleTypes
 * Справочник типов расписания
 * @package app\components\entity
 * @property int $id [int]  Идентификатор записи
 * @property string $date [date]  Дата проведения экзамена
 * @property int $exam_id [int]  Ссылка на экзамен
 * @property int $type_id [int]  Ссылка на справочник типов расписания
 * @property Exams $exam [object] Экзамен
 * @property ScheduleTypes $type [object] Тип расписания
 */
class Schedule extends ActiveRecord
{
    /**
     * Сформировано
     * @var int
     */
    const STATUS_DONE = 1;

    /**
     * Не сформировано
     * @var int
     */
    const STATUS_NOT_DONE = 2;

    /**
     * Отсутствует день подготовки
     * @var int
     */
    const STATUS_CANCEL = 3;

    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{schedule}}';
    }

    /**
     * Экзамен
     * @return ActiveQuery
     */
    public function getExam()
    {
        return $this->hasOne(Exams::class, ['id' => 'exam_id']);
    }

    /**
     * Тип расписания
     * @return ActiveQuery
     */
    public function getType()
    {
        return $this->hasOne(ScheduleTypes::class, ['id' => 'type_id']);
    }
}
