<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\CategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = 'Новости';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Создать Новость', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Создать Категорию', ['category-create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'title',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete} {news-not-visible} {news-visible} {news-comment}',
                'buttons' => [
                    'create' => function ($url, $model, $key) {
                         return Html::a('<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>', $url);
                    },

                    'news-not-visible' => function ($url, $model, $key) {
                        return Html::a('Кто не смотрел', $url);
                    },
                    'news-visible' => function ($url, $model, $key) {
                        return Html::a('|Кто смотрел', $url);
                    },
                    'news-comment' => function ($url, $model, $key) {
                        return Html::a('|Комментарий', $url);
                    },
                ],
            ], 'date_created'
        ],
    ]); ?>

</div>
