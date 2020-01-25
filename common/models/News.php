<?php

namespace common\models;

use Yii;
use common\models\User;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\db\ActiveRecord;
use yii\helpers\Html;

/**
 * This is the model class for table "news".
 *
 * @property int $id
 * @property string $title
 * @property string $content
 * @property int $status
 * @property string $date_created
 */
class News extends ActiveRecord
{
    public $file;
    
    const STATUS_ACTIVE = 1;
    const STATUS_MODERATION = 2;
    const STATUS_REFUSED = 0;

    public function behaviors(){
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['date_created'],
                ],
                // если вместо метки времени UNIX используется datetime:
                'value' => new Expression('NOW()'),
            ],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'news';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['content'], 'string'],
            //[['status'], 'required'],
            ['status', 'default', 'value' => 1],
            [['status', 'category_id'], 'integer'],
            [['date_created'], 'safe'],
            [['title'], 'string', 'max' => 200],
            [['file'], 'file', 'maxFiles' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category_id' => 'Категория',
            'title' => 'Title',
            'content' => 'Content',
            'status' => 'Status',
            'date_created' => 'Date Created',
        ];
    }
    public function afterSave($insert, $changedAttributes)
    {
        if ($insert) {
            // Да это новая запись (insert)
            $email_list = User::find()->select("email")->asArray()->column();
            $messages = [];

            foreach ($email_list as $email) 
            {
                $details_link = Html::a('Подробнее', ['news/detail', 'id' => $this->id]);
                $details_link_new = str_replace('backend', 'frontend', $details_link);
                $messages[] = Yii::$app->mailer->compose()->setFrom(['newswork@frlw.info' => $this->title])
                    ->setSubject($this->title)
                    //->setHtmlBody($this->content . '<br>' . $details_link_new )
                    ->setHtmlBody($this->content)
                    ->setTo($email);              
            }

            Yii::$app->mailer->sendMultiple($messages);
        } 
        else 
        {
                // Нет, старая (update)
        }

        return parent::afterSave($insert, $changedAttributes);    
    }

    public static function getStatusName()
    {
        $statuses = [
            0 => 'Отклонено',
            1 => 'Опубликовано',
            2 => 'На модерации',
        ];

        return $statuses;
    }

    public function getNewsmedia()
    {
        return $this->hasMany(Newsmedia::className(), ['news_id' => 'id']);
    }

    public function getListPreview()
    {
        $list_media = $this->hasMany(Newsmedia::className(), ['news_id' => 'id'])->all();
        $list_placeholder_source = [];
        foreach ($list_media as $media)
        {
            //$file_path = Yii::$app->request->baseUrl . '/images/file_placeholder.jpg';
            //$fileInfo = pathinfo($media->name); 
            //if ($fileInfo["extension"] == "jpg" || $fileInfo["extension"] == "png") { $file_path = Yii::$app->request->baseUrl . '/uploads/' . $media->name ; }
            //if ($fileInfo["extension"] == "pdf") { $file_path = Yii::$app->request->baseUrl . '/images/pdf_placeholder.png'; }
            //if ($fileInfo["extension"] == "xlsx") { $file_path = Yii::$app->request->baseUrl . '/images/excel_placeholder.jpg'; }
            $fileInfo = pathinfo($media->name);
            $file_path = Yii::$app->request->baseUrl . '/uploads/' . $media->name ;
            if ($fileInfo["extension"] == "pdf") { $file_path = Yii::$app->request->baseUrl . '/images/pdf_placeholder.png'; }
            if ($fileInfo["extension"] == "xlsx") { $file_path = Yii::$app->request->baseUrl . '/images/excel_placeholder.jpg'; }
            $list_placeholder_source [] = $file_path;
        }

        return $list_placeholder_source;
    }

    public function getListPreviewConfig()
    {
        $list_media = $this->hasMany(Newsmedia::className(), ['news_id' => 'id'])->all();
        $list_key_source = [];
        foreach ($list_media as $media)
        {
            $list_key_source [] = ['key' => $media->id];
        }

        return $list_key_source;
    }
    
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }
}
