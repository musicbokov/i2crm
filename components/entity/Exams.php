<?php

namespace app\components\entity;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Class Exams
 * Экзамены
 * @package app\components\entity
 * @property int $id [int]  Идентификатор записи
 * @property string $name [varchar(10)]  Название экзамена
 * @property int $days [int]  Кол-во дней для подготовки
 * @property Schedule[] $schedule Расписания
 */
class Exams extends ActiveRecord
{

    /**
     * Наименование таблицы
     * @return string
     */
    public static function tableName()
    {
        return '{{exams}}';
    }

    /**
     * Получить расписания
     * @return ActiveQuery
     */
    public function getSchedule()
    {
        return $this->hasMany(Schedule::class, ['exam_id' => 'id']);
    }
}
