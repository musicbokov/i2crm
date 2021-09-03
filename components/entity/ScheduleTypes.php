<?php

namespace app\components\entity;

use yii\db\ActiveRecord;

/**
 * Class ScheduleTypes
 * Справочник типов расписания
 * @package app\components\entity
 * @property int $id [int]  Идентификатор записи
 * @property string $name [varchar(10)]  Наименование типа
 */
class ScheduleTypes extends ActiveRecord
{
    /**
     * Экзамен
     * @var int
     */
    const TYPE_EXAM = 1;

    /**
     * Подготовка
     * @var int
     */
    const TYPE_PREPARING = 2;

    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{schedule_types}}';
    }
}
