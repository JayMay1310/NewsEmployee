<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "newsview".
 *
 * @property int $id
 * @property int $user_id
 * @property int $news_id
 * @property string $date_view
 */
class Newsview extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'newsview';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'news_id'], 'required'],
            [['user_id', 'news_id'], 'integer'],
            [['date_view'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'news_id' => 'News ID',
            'date_view' => 'Date View',
        ];
    }

    public static function isUserRead($user_id, $news_id)
    {
        $result = static::find()->where(['user_id' => $user_id, 'news_id'=> $news_id])->one();
        return isset($result);
    }
    
    public static function getAllUserReadNews($news_id)
    {
        return static::find()->where(['news_id'=> $news_id])->select('user_id')->asArray()->all();
    }

    public static function getUserDataRead($user_id, $news_id)
    {
        $result = static::find()->where(['user_id' => $user_id, 'news_id'=> $news_id])->select('date_view')->asArray()->one();
        return $result;
    }
}
