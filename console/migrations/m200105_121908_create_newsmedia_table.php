<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%newsmedia}}`.
 */
class m200105_121908_create_newsmedia_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%newsmedia}}', [
            'id' => $this->primaryKey(),
            'news_id' => $this->integer()->notNull(),
            'name' => $this->string(300),
            'origname' => $this->string(),
            'created_date' => $this->dateTime(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%newsmedia}}');
    }
}
