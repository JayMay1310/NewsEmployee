<?php
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $model common\models\Category */
/* @var $categories common\models\Category[] */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="category-form">
    <?php $form = ActiveForm::begin(['id' => 'category-update','options' => ['method' => 'post']]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => 255]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>