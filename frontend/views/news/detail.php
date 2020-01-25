<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

$media = $model->newsmedia;

?>

<div class="col-md-9">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Новость</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">
              <div class="mailbox-read-info">
                <h3><?= $model->title ?></h3>
              </div>
              <!-- /.mailbox-read-info -->

              <!-- /.mailbox-controls -->
              <div class="mailbox-read-message">
                <?= $model->content ?>
              </div>
              <!-- /.mailbox-read-message -->
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
              <ul class="mailbox-attachments clearfix">

              <?php foreach ($media as $item): ?>
                <?php
                  $file_icon = 'fa fa-file-o'; 
                  $fileInfo = pathinfo($item->name); 
                  if ($fileInfo["extension"] == "jpg" || $fileInfo["extension"] =="png") 
                  {   
                    $path = Yii::$app->urlManagerBackend->baseUrl . '/web/uploads/' .  $item->name;
                    $file_icon = '<span class="mailbox-attachment-icon has-img">' . Html::img($path, ['alt' => 'Attachment']) . '</span>'; 
                  }
                  //if ($fileInfo["extension"] == "pdf") { $file_icon = '<span class="mailbox-attachment-icon"><i class="fa fa-file-pdf-o"></i></span>'; } 
                  else { $file_icon = '<span class="mailbox-attachment-icon"><i class="fa fa-file"></i></span>';}
                ?>               
              <li>
                  <?= $file_icon ?>                
                  <div class="mailbox-attachment-info">
                     <?= Html::a('Скачать', ['news/download', 'id' => $item->id], ['class'=>'mailbox-attachment-name']) ?>
                  </div>
              </li>

              <?php endforeach; ?>
            </ul>
          </div>
            <!-- /.box-footer -->
          <div class="box-footer">
              <?php
              if ($isUserReadNews)
              {
                echo Html::tag('p', 'Вы уже прочитали эту новость', ['class' => 'username']);
              }
              else
              {
                echo Html::a('Ознакомился', ['news/read', 'id' => $model->id], ['class'=>'btn btn-primary btn-lg']); 
              }
              ?>
              
          </div>
            <!-- /.box-footer -->
          </div>
          <!-- /. box -->
        </div>
        <?php echo \yii2mod\comments\widgets\Comment::widget([
                'model' => $model,
        ]); ?>