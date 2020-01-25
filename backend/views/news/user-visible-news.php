<?php
use common\models\Newsview;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\CategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = 'Кто прочитал новость';
$this->params['breadcrumbs'][] = $this->title;


?>
<div class="category-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'username','email', 'name', 'lastname',
            [ 
                'label' => 'Когда прочитано',
                'value' => function ($model) {
                    $news_id = Yii::$app->request->queryParams['id']; 
                    $dateRead = Newsview::getUserDataRead($model->id, $news_id);
   
                    return $dateRead['date_view']; 
                } 
           ],

        ],
    ]); ?>

</div>