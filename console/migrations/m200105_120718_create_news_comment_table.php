<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `{{%news_comment}}`.
 */
class m200105_120718_create_news_comment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%news_comment}}', [
            'id' => Schema::TYPE_PK,
            'news_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'content' => Schema::TYPE_TEXT . ' NOT NULL',
            'user_id' => Schema::TYPE_INTEGER . '',
            'created_at' => Schema::TYPE_INTEGER . ' NOT NULL',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%news_comment}}');
    }
}
