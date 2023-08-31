<?php


namespace backend\controllers;


use api\models\House;
use api\models\Neighborhood;
use backend\models\Notification;
use backend\utils\FirebaseApi;
use backend\utils\Tools;
use common\models\Category;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\UploadedFile;

class NotificationController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'view'],
                        'allow' => true,
                        'roles' => ['@']
                    ],
                    [
                        'actions' => ['create'],
                        'allow' => true,
                        'roles' => ['createNotification']
                    ],
                    [
                        'actions' => ['delete'],
                        'allow' => true,
                        'roles' => ['deleteNotification'],
                        'roleParams' => function () {
                            return ['post' => Category::findOne(['id' => Yii::$app->request->get('id')])];
                        },
                    ],
                    [
                        'actions' => ['update'],
                        'allow' => true,
                        'roles' => ['updateNotification'],
                        'roleParams' => function () {
                            return ['post' => Category::findOne(['id' => Yii::$app->request->get('id')])];
                        },
                    ],
                    [
                        'actions' => ['status'],
                        'allow' => true,
                        'roles' => ['sendNotification'],
                        'roleParams' => function () {
                            return ['post' =>
                                Notification::findOne(
                                    [
                                        'id' => Yii::$app->request->get('id')
                                    ])
                            ];
                        }
                    ],
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }


    public function actionCreate()
    {
        $model = new Notification();
        $model->time_to_live = Notification::ONE_WEEK;
        if ($model->load(Yii::$app->request->post())) {
            $model->small_icon = UploadedFile::getInstance($model, 'small_icon');
            $model->large_icon = UploadedFile::getInstance($model, 'large_icon');
            if ($model->saveModel()) {
                return $this->redirect(['index']);
            }
        }
        return $this->render('form', ['model' => $model]);
    }

    public function actionUpdate($id)
    {
        $model = Notification::findOne($id);
        if ($model->load(Yii::$app->request->post())) {
            $model->small_icon = UploadedFile::getInstance($model, 'small_icon');
            $model->large_icon = UploadedFile::getInstance($model, 'large_icon');
            if ($model->saveModel()) {
                return $this->redirect(['index']);
            }
        }
        return $this->render('form', ['model' => $model]);
    }

    public function actionDelete($id)
    {
        if (!Yii::$app->request->isAjax) {
            return $this->redirect(['index']);
        }
        $model = Notification::findOne($id);
        $model->deleteSmallIcon();
        $model->deleteLargeIcon();
        $model->delete();
    }

    public function actionStatus($id)
    {
        $sent = 0;
        if (!Yii::$app->request->isAjax) {
            return $this->redirect(['index']);
        }
        $model = Notification::findOne($id);
        $firebaseApi = new FirebaseApi();
        $firebaseApi->setTitle($model->title);
        $firebaseApi->setSmallBody($model->small_body);
        $firebaseApi->setBigBody($model->big_body);
        $firebaseApi->setTimeToLive($model->time_to_live);
        $smallIconUrl = $model->getSmallIconPath();
        if(isset($smallIconUrl)){
            $firebaseApi->setSmallIcon($smallIconUrl);
        }
        $largeIconUrl = $model->getLargeIconUrl();
        if(isset($largeIconUrl)){
            $firebaseApi->setLargeIcon($largeIconUrl);
        }
        $responseCode = $firebaseApi->sendData();
        if ($responseCode >= 200 && $responseCode < 300) {
            $model->status = Notification::STATUS_SENT;
            if($model->save()){
                $sent = 200;
            }
        }
        return Json::encode(['status' => $sent]);
    }

    public function actionIndex()
    {
        $query = Notification::find();
        $query->joinWith(['author' => function ($query) {
            $query->from(['author' => 'user']);
        }]);
        $query->addOrderBy([
            'created_at' => SORT_DESC,
        ]);
        $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => 12
                ]
            ]
        );
        $dataProvider->sort->attributes['author.username'] = [
            'asc' => ['author.username' => SORT_ASC],
            'desc' => ['author.username' => SORT_DESC],
        ];
        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }
}