<?php

namespace frontend\controllers;


use Yii;
use yii\web\Response;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

use yii\web\NotFoundHttpException;

use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\db\Expression;


use common\models\News;
use common\models\Newsview;
use common\models\Newsmedia;
use common\models\NewsSearch;
use common\models\Category;


class NewsController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup', 'index' ],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new NewsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if(!Yii::$app->request->get('page')){
            $dataProvider->pagination->page = ceil($dataProvider->getTotalCount() / $dataProvider->pagination->pageSize) - 1;
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'pagination' => $dataProvider->getPagination(),
        ]);
    }
    public function actionDetail($id)
    {
        $isUserReadNews = Newsview::isUserRead(Yii::$app->user->getId(), $id);
        return $this->render('detail', [
            'model' => $this->findModel($id),
            'isUserReadNews' => $isUserReadNews,
        ]);
    }

    public function actionCategoryView($id)
    {
        $category = Category::findOne($id);
        $query = $category->getNews();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 50,
            ],
            'sort' => [
                'defaultOrder' => [
                    'date_created' => SORT_DESC,
                ]
            ],
        ]);

        return $this->render('category-view', [
            'dataProvider' => $dataProvider,
            'pagination' => $dataProvider->getPagination(),
        ]);
    }

    public function actionRead($id)
    {
        $model = new Newsview();
        $model->user_id = Yii::$app->user->getId();
        $model->news_id = $id;
        $model->date_view = new Expression('NOW()');
        $model->save();

        Yii::$app->session->setFlash('success', 'Новость просмотрена');
        return $this->redirect(['detail', 'id' => $id]);
    }

    public function actionReadCheckbox()
    {
        $action=Yii::$app->request->post('action');
        $selection=(array)Yii::$app->request->post('selection');

        foreach($selection as $id){
            $model = new Newsview();
            $model->user_id = Yii::$app->user->getId();
            $model->news_id = $id;
            $model->date_view = new Expression('NOW()');
            $model->save();
       }
        Yii::$app->session->setFlash('success', 'Новости просмотрены');
        return $this->redirect(['news/index']);
    }

    public function actionDownload($id) 
    {
        $model = Newsmedia::findOne($id);
        $path = Yii::getAlias('@backend');
        $file = $path . '/web/uploads/' .  $model->name;
      
        if (file_exists($file)) 
        {
            $new_file_path = $path . '/web/uploads/' . $model->origname;
            copy($file, $new_file_path);
            return Yii::$app->response->sendFile($new_file_path)->on(Response::EVENT_AFTER_SEND, function($event) {
                unlink($event->data);
            }, $new_file_path);
        }
        
        throw new \Exception('File not found');
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
