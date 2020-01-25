<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%newsview}}`.
 */
class m200105_100806_create_newsview_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%newsview}}', [
            'id' => $this->primaryKey(),
            'news_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'date_view' => $this->dateTime(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%newsview}}');
    }
}
