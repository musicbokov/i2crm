<?php

namespace app\components\entity;

use yii\db\ActiveRecord;

/**
 * Class Exams
 * Экзамены
 * @package app\components\entity
 * @property int $id [int]  Идентификатор записи
 * @property string $name [varchar(10)]  Название экзамена
 * @property int $days [int]  Кол-во дней для подготовки
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
}
