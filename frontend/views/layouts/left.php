<?php

use yii\helpers\Url;
use common\models\Category;

$categorys = Category::find()->all();

$category = [];
foreach ($categorys as $item_category) 
{
    $category[] = ['label' => $item_category->title, 'url' => ['news/category-view?id=' . $item_category->id], 'visible' => !Yii::$app->user->isGuest];  
}
$category [] = ['label' => 'Все новости', 'url' => ['news/index'], 'visible' => !Yii::$app->user->isGuest];
$category [] = ['label' => 'Выйти', 'url' => ['site/logout'], 'visible' => !Yii::$app->user->isGuest];

    
?>

<aside class="main-sidebar">
    <section class="sidebar">
        <!-- search form -->
        <!-- /.search form -->
        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
                'items' => $category                                   
            ]
        ) ?>

    </section>
</aside>
