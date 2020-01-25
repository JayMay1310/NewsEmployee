<?php
use common\models\Newsview;
use common\models\User;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\CategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = 'Комментарий к новости';
$this->params['breadcrumbs'][] = $this->title;


?>
<div class="category-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?=Html::beginForm(['news/comment-delete'],'post');?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'class' => 'yii\grid\CheckboxColumn',
                'checkboxOptions' => function($model, $key, $index, $widget) {
                   return ['value' => $model['id'] ];
                 },
            ],
            'content',
            [
                'label' => 'Автор',
                'value' => function ($model, $index, $widget) {
                    $user = User::findOne($model->createdBy);
                    $name = $user->lastname . ' ' . $user->name;
                    return $name;
                },               
            ],
            [
                'attribute'=>'updatedAt',
                'value' => function ($model, $index, $widget) {
                    $dates = date('Y-m-d H:m:s', $model->updatedAt);
                    return $dates;
                },               
            ],
        ],
    ]);    ?>

    <?=Html::submitButton('Удалить', ['class' => 'btn btn-info',]);?>
    <?= Html::endForm();?> 


</div>