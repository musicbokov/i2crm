<?php

namespace app\components\entity;

use yii\db\ActiveRecord;

/**
 * Class ExceptionsCode
 * Справочник кодов ошибок
 * @package app\components\entity
 * @property int $id [int]  Идентификатор записи
 * @property string $name [varchar(10)]  Наименование типа
 * @property int $code [int]  Код ошибки
 */
class ExceptionsCode extends ActiveRecord
{
    /**
     * Экзамен с таким названием уже зарегистрирован
     * @var int
     */
    const CODE_NAME_EXIST = 1;

    /**
     * Подготовка
     * @var int
     */
    const CODE_DATE_EXIST = 2;

    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{exceptions_code}}';
    }
}
