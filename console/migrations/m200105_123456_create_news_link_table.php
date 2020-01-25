<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%news_link}}`.
 */
class m200105_123456_create_news_link_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%news_link}}', [
            'id' => $this->primaryKey(),
            'news_id' => $this->integer()->notNull(),
            'title' => $this->string(),
            'link' => $this->string(100),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%news_link}}');
    }
}
