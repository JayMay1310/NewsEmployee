<?php

namespace backend\controllers;

use Yii;
use common\models\News;
use common\models\Newsview;
use common\models\Newsmedia;
use common\models\NewsSearch;
use common\models\User;
use common\models\Category;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

use yii\db\Expression;
use yii\data\ActiveDataProvider;

use backend\models\UploadForm;
use yii\web\UploadedFile;

use yii2mod\comments\models\CommentModel;


/**
 * NewsController implements the CRUD actions for News model.
 */
class NewsController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all News models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new NewsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single News model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new News model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $categories = Category::find()->all();

        $model = new News();
        $fileForm = new UploadForm();
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $fileForm->file = UploadedFile::getInstances($model, 'file');
            foreach ($fileForm->file as $file) {
                $file->saveAs(Yii::getAlias('@webroot') . '/uploads/' . md5($model->id . '-' . $file->baseName) . '.' . $file->extension);
                $media = new Newsmedia();
                $media->news_id = $model->id;
                $media->name = md5($model->id . '-' . $file->baseName) . '.' . $file->extension;
                $media->origname = $file->baseName . '.' . $file->extension;
                $media->created_date = new Expression('NOW()');
                $media->save(false);              
            }
                      
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
            'categories' => $categories,
        ]);
    }

    /**
     * Updates an existing News model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $categories = Category::find()->all();
        $model = $this->findModel($id);
        $fileForm = new UploadForm();
        $test = $model->newsmedia;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $fileForm->file = UploadedFile::getInstances($model, 'file');
            foreach ($fileForm->file as $file) {
                $file->saveAs(Yii::getAlias('@webroot') . '/uploads/' . md5($model->id . '-' . $file->baseName) . '.' . $file->extension);
                $media = new Newsmedia();
                $media->news_id = $model->id;
                $media->name = md5($model->id . '-' . $file->baseName) . '.' . $file->extension;
                $media->created_date = new Expression('NOW()');
                $media->origname = $file->baseName . '.' . $file->extension;
                $media->save(false);              
            }

            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'categories' => $categories,
        ]);
    }

    /**
     * Deletes an existing News model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }


    public function actionFileUpload()
    {
        if(Yii::$app->request->isAjax)
        {
            Yii::$app->response->format = Response::FORMAT_JSON;

            //$model = new UploadForm();
            
            //$keys = $_POST;
            //$model->file = UploadedFile::getInstances($model, 'attachment');
        
            //foreach ($model->file as $file) {
            //    $file->saveAs(Yii::getAlias('@webroot') . '/uploads/' . $model->file->baseName . '.' . $model->file->extension);
            //    $test = $file->tempName;
            //    echo '';
            //}

            return '{}';
        }
    }

    public function actionFileDelete()
    {
        if(Yii::$app->request->isAjax)
        {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $key = $_POST['key'];

            if (is_numeric($key)) 
            {
                $news_media = Newsmedia::find()->where(['id' => $key])->one();
                $news_media->delete();
                unlink(Yii::getAlias('@webroot') . '/uploads/' . $news_media->name);                
                return '{true}';
            }   

            return '{}';
        }
    }

    //Контроллер кто не смотрел
    public function actionNewsNotVisible($id)
    {
        $listIdUser = Newsview::getAllUserReadNews($id);

        $list = [];
        foreach ($listIdUser as $itemId)
        {
            $list[] = $itemId['user_id'];
        }

        $query = User::find()->where(['not in', 'id', $list]);
        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 50,
            ],
        ]);
        return $this->render('user-not-visible-news', [
            'dataProvider' => $provider,          
        ]);
    }

    public function actionNewsComment($id)
    {
        $query = CommentModel::find()->where(['=', 'entityId', $id]);

        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 50,
            ],
        ]);

        return $this->render('news-comment', [
            'dataProvider' => $provider,          
        ]);
    }

    public function actionCommentDelete()
    {
        $selection=(array)Yii::$app->request->post('selection');
 
        foreach($selection as $id){
            $model = CommentModel::findOne((int)$id);
            $model->isRoot() ? $model->deleteWithChildren(): $model->delete();
       }

       return $this->redirect(['news/index']); 
    }

    public function actionCategoryList()
    {
        $query = Category::find()->orderBy('title ASC, id ASC');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        
        return $this->render('category-list', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCategoryDelete($id)
    {
        Category::findOne($id)->delete();
        return $this->redirect(['category-list']);
    }

    //Контроллер кто смотрел
    public function actionNewsVisible($id)
    {
        $listIdUser = Newsview::getAllUserReadNews($id);

        $list = [];
        foreach ($listIdUser as $itemId)
        {
            $list[] = $itemId['user_id'];
        }

        $query = User::find()->where(['in', 'id', $list]);
        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 50,
            ],
        ]);
        return $this->render('user-visible-news', [
            'dataProvider' => $provider,          
        ]);     
    }

    public function actionCategoryCreate()
    {
        $categories = Category::find()->all();
        $model = new Category();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['category-view', 'id' => $model->id]);
        } else {
            return $this->render('category-create', [
                'model' => $model,
                'categories' => $categories,
            ]);
        }
    }

    public function actionCategoryView($id)
    {
        return $this->render('category-view', [
            'model' => Category::findOne($id),
        ]);
    }

    public function actionCategoryUpdate($id)
    {
        $categories = Category::find()->all();
        $model = Category::findOne($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['category-list']);
        } else {
            return $this->render('category-update', [
                'model' => $model,
                'categories' => $categories,
            ]);
        }
    }

    /**
     * Finds the News model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return News the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = News::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
