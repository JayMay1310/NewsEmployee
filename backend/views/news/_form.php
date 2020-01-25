<?php
use yii\helpers\ArrayHelper;

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\tinymce\TinyMce;
use common\models\News;

use kartik\file\FileInput;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\News */
/* @var $form yii\widgets\ActiveForm */

$listCategory = ArrayHelper::map($categories, 'id', 'title');
$select = 0;
if (count($listCategory) != 0)
{
    $select = array_key_first($listCategory);
}

?>

<div class="news-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'category_id')->dropDownList($listCategory, ['prompt' => 'Выберите раздел', 'options' => [ $select => ['Selected'=>'selected']]]) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'content')->widget(TinyMce::className(), [
            'options' => ['rows' => 30],
            'language' => 'ru',
            'clientOptions' => [
            'plugins' => [
                'advlist autolink lists link charmap  print hr preview pagebreak',
                'searchreplace wordcount visualblocks visualchars code fullscreen nonbreaking',
                'save insertdatetime media table template paste image codesample'
                ],
                'toolbar' => 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image'
            ]
        ]);
    ?>

    <?php echo $form->field($model, 'file[]')->widget(FileInput::classname(), [
            'name' => 'file[]',
            'options'=>[
                'multiple'=>true
            ],
            'pluginOptions' => [
                'uploadUrl' => Url::to(['/news/file-upload']),
                'deleteUrl' => Url::to(['/news/file-delete']),
                'uploadExtraData' => [
                    'album_id' => 20,
                    'cat_id' => 'Nature'
                ],
                'showUpload' => false,
                'initialPreview' => $model->listpreview,
                'initialPreviewConfig' =>  $model->listpreviewconfig,
                'initialPreviewAsData'=>true,
            'maxFileCount' => 50
            ]
        ]);
    ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>



</div>
