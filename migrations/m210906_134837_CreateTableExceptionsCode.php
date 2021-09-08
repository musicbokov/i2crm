<?php

use yii\db\Migration;

/**
 * Class m210906_134837_CreateTableExceptionsCode
 * Миграция по созданию таблицы "Справочник кодов ошибок"
 */
class m210906_134837_CreateTableExceptionsCode extends Migration
{

    /**
     * Наименование таблицы
     * @var string
     */
    private $tableName = 'exceptions_code';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        /** Создание таблицы */
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey()->defaultValue('SERIAL')->notNull()->comment('Идентификатор записи'),
            'code' => $this->integer()->unique()->notNull()->comment('Код ошибки'),
            'name' => $this->string()->comment('Наименование ошибки'),
        ]);
        $this->addCommentOnTable($this->tableName, 'Справочник кодов ошибок');

        /** Добавление записей по-умолчанию */
        //Экзамен с таким названием уже зарегистрирован
        $this->insert($this->tableName, [
            'code' => 1,
            'name' => 'Экзамен с таким названием уже зарегистрирован'
        ]);
        //На ([дата]) уже назначено проведение другого экзамена ([экзамен])
        $this->insert($this->tableName, [
            'code' => 2,
            'name' => 'На [дата] уже назначено проведение другого экзамена ([экзамен])'
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
