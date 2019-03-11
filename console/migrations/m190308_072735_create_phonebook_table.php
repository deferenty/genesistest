<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%phonebook}}`.
 */
class m190308_072735_create_phonebook_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {        
        $this->createTable('{{%phonebook}}', [
            'id' => $this->primaryKey(),
            'firstName' => $this->string(30),
            'lastName' => $this->string(30),
            'phoneNumbers' => $this->json()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%phonebook}}');
    }
}
