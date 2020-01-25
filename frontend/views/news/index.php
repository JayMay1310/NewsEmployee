<?php

use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\helpers\StringHelper;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use common\models\Newsview;

?>

<div class="row">    
    <div class="col-md-11">
        <?=Html::beginForm(['news/read-checkbox'],'post');?>
          <div class="box box-primary">
            <div class="box-header with-border">
              <div class="box-tools pull-right">
                <div class="has-feedback">
                  <!--<input type="text" class="form-control input-sm" placeholder="Search">
                  <span class="glyphicon glyphicon-search form-control-feedback"></span>
                  -->
                </div>
              </div>
              <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">
              <div class="mailbox-controls">
                <!-- Check all button -->
                <button type="button" class="btn btn-default btn-sm checkbox-toggle"><i class="fa fa-square-o"></i>
                </button>

                <!-- /.btn-group -->

                <div class="pull-right">
                  <div class="btn-group">
                  <?= LinkPager::widget([
                                'pagination' => $pagination,
                                'prevPageLabel' => '<i class="fa fa-chevron-left"></i>',
                                'nextPageLabel' => '<i class="fa fa-chevron-right"></i>',
                                'maxButtonCount' => 0,
                            ]); ?>
                  </div>
                  
                  <!-- /.btn-group -->
                </div>
                <!-- /.pull-right -->
              </div>
              <div class="table-responsive mailbox-messages">
                <table class="table table-hover table-striped">
                  <tbody>
                  <?php foreach ($dataProvider->models as $model): ?>
                    <?php
                        $read_cells = '<span class="label label-success">Прочитано</span>';
                        $isUserReadNews = Newsview::isUserRead(Yii::$app->user->getId(), $model->id);
                        if (!$isUserReadNews)
                        {
                            $read_cells = '<span class="label label-danger">Не прочитано</span>';
                        }
                    ?>
                    <tr>
                        <td><input type="checkbox" name="selection[]" value= <?= "$model->id" ?>></td>
                        <td class="mailbox-name"><?= Html::a(Html::encode($model->title), ['detail', 'id' => $model->id]) ?></td>
                        <td class="mailbox-subject"><?= StringHelper::truncateWords(strip_tags($model->content), 20, $suffix = "...", $asHtml = true) ?></td>
                        <td class="mailbox-subject"><?= $model->category->title ?></td>
                        <td class="mailbox-subject"><?= $read_cells ?></td>
                        <td class="mailbox-date"><?= $model->date_created ?></td>
                    </tr>
                  <?php endforeach; ?>
                  </tbody>
                </table>
                <!-- /.table -->
              </div>
              <!-- /.mail-box-messages -->
            </div>
            <!-- /.box-body -->
            <div class="box-footer no-padding">
              <div class="mailbox-controls">
                <!-- Check all button -->
 
                <div class="btn-group">

                </div>
                <!-- /.btn-group -->
                <div class="pull-right">
                  <div class="btn-group">
                    <?= LinkPager::widget([
                            'pagination' => $pagination,
                            'prevPageLabel' => '<i class="fa fa-chevron-left"></i>',
                            'nextPageLabel' => '<i class="fa fa-chevron-right"></i>',
                            'maxButtonCount' => 0,
                        ]); ?>
                  </div>
                  <!-- /.btn-group -->
                </div>
                <!-- /.pull-right -->
              </div>
            </div>
              <?=Html::submitButton('Ознакомился', ['class' => 'btn btn-primary btn-lg',]);?>
            <?= Html::endForm();?> 
          </div>
          <!-- /. box -->
    </div>
</div>
</div>        


