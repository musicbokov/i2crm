<?php

use yii\db\Migration;

/**
 * Class m210903_115520_CreateTableScheduleTypes
 * Миграция по созданию таблицы "Справочник типов расписания"
 */
class m210903_115520_CreateTableScheduleTypes extends Migration
{

    /**
     * Наименование таблицы
     * @var string
     */
    private $tableName = 'schedule_types';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        /** Создание таблицы */
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey()->defaultValue('SERIAL')->notNull()->comment('Идентификатор записи'),
            'name' => $this->string(10)->unique()->comment('Наименование типа'),
        ]);
        $this->addCommentOnTable($this->tableName, 'Справочник типов расписания');

        /** Добавление записей по-умолчанию */
        //Экзамен
        $this->insert($this->tableName, [
            'name' => 'Экзамен'
        ]);
        //Подготовка
        $this->insert($this->tableName, [
            'name' => 'Подготовка'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
