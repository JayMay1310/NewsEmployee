<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;


/* @var $this yii\web\View */
/* @var $searchModel backend\models\CategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = 'Categories';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Category', ['category-create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
            'title',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{create} {view} {update} {delete}',
                'buttons' => [
                    'create' => function ($url, $model, $key) {
                         return Html::a('<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>', 'category-create');
                    },
                    'view' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>', Url::to(['category-view', 'id' => $model->id]));
                    },
                    'update' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>', Url::to(['category-update', 'id' => $model->id]));
                    },
                    'delete' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>', Url::to(['category-delete', 'id' => $model->id]));
                    },
                ],
            ], 
        ],
    ]); ?>

</div>