<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
/**
 * This is the model class for table "newsmedia".
 *
 * @property int $id
 * @property int $news_id
 * @property string $name
 * @property string $created_date
 */
class Newsmedia extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'newsmedia';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['news_id'], 'required'],
            [['news_id'], 'integer'],
            [['created_date'], 'safe'],
            [['origname'], 'string'],
            [['name'], 'string', 'max' => 300],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'news_id' => 'News ID',
            'name' => 'Name',
            'created_date' => 'Created Date',
        ];
    }

}
