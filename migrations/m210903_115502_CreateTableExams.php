<?php

use yii\db\Migration;

/**
 * Class m210903_115502_CreateTableExams
 * Миграция по созданию таблицы "Экзамены"
 */
class m210903_115502_CreateTableExams extends Migration
{

    /**
     * Наименование таблицы
     * @var string
     */
    private $tableName = 'exams';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        /** Создание таблицы */
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey()->defaultValue('SERIAL')->notNull()->comment('Идентификатор записи'),
            'name' => $this->string(10)->unique()->comment('Название экзамена'),
            'days' => $this->integer()->comment('Кол-во дней для подготовки')
        ]);
        $this->addCommentOnTable($this->tableName, 'Экзамены');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
