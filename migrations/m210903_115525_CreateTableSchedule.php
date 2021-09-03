<?php

use yii\db\Migration;

/**
 * Class m210903_115525_CreateTableSchedule
 * Миграция по созданию таблицы "Расписание экзаменов и подготовки"
 */
class m210903_115525_CreateTableSchedule extends Migration
{

    /**
     * Наименование таблицы
     * @var string
     */
    private $tableName = 'schedule';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        /** Создание таблицы */
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey()->defaultValue('SERIAL')->notNull()->comment('Идентификатор записи'),
            'date' => $this->date()->comment('Дата проведения экзамена'),
            'exam_id' => $this->integer()->comment('Ссылка на экзамен'),
            'type_id' => $this->integer()->comment('Ссылка на справочник типов расписания')
        ]);
        $this->addCommentOnTable($this->tableName, 'Расписание экзаменов и подготовки');

        /** Ограничение внешнего ключа */
        //Связь таблицы "Расписание экзаменов и подготовки" (exam_id) с таблицей "Экзамены" (id)
        $this->addForeignKey(
            'FK_SCHEDULE_EXAMS',
            $this->tableName,
            'exam_id',
            'exams',
            'id',
            'cascade',
            'cascade'
        );

        //Связь таблицы "Расписание экзаменов и подготовки" (type_id) с таблицей "Справочник типов расписания" (id)
        $this->addForeignKey(
            'FK_SCHEDULE_SCHEDULE_TYPES',
            $this->tableName,
            'type_id',
            'schedule_types',
            'id',
            'set null',
            'set null'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
