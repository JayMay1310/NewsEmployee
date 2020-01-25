<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%news}}`.
 */
class m200105_095526_create_news_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%category}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(200),
            'slug' => $this->string(200),
        ], $tableOptions);

        $this->createTable('{{%news}}', [
            'id' => $this->primaryKey(),
            'category_id' => $this->integer()->notNull(),
            'title' => $this->string(200),
            'content' => $this->text(),
            'status' => $this->integer()->notNull(),
            'date_created' => $this->dateTime(),
        ]);

        $this->createIndex(
            'idx-post-category_id',
            'news',
            'category_id'
        );

        $this->addForeignKey(
            'fk-post-category_id',
            'news',
            'category_id',
            'category',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-post-category_id',
            'news'
        );

        // drops index for column `category_id`
        $this->dropIndex(
            'idx-post-category_id',
            'news'
        );

        $this->dropTable('{{%news}}');
    }
}
