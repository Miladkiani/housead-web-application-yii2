<?php


namespace backend\controllers;
use common\models\Feature;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
class FeatureController extends Controller
{
    public function behaviors()
    {
        return [
            'access'=>[
                'class'=>AccessControl::className(),
                'rules'=>[
                    [
                        'actions'=>['index','view'],
                        'allow'=>true,
                        'roles'=>['@']
                    ],
                    [
                        'actions'=>['create'],
                        'allow'=>true,
                        'roles'=>['createFeature']
                    ],
                    [
                        'actions'=>['delete'],
                        'allow'=>true,
                        'roles'=>['deleteFeature'],
                        'roleParams' => function() {
                            return ['post' => Feature::findOne(['id' => Yii::$app->request->get('id')])];
                        },
                    ],
                    [
                        'actions'=>['update'],
                        'allow'=>true,
                        'roles' => ['updateFeature'],
                        'roleParams' => function() {
                            return ['post' => Feature::findOne(['id' => Yii::$app->request->get('id')])];
                        },
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
       $model = new Feature();
       if ($model->load(Yii::$app->request->post()) && $model->validate()) {
           if ($model->saveModel($_POST['Feature']['categories'])) {
               return $this->redirect(['index']);
           }
       }
           return $this->render('form', ['model' => $model]);
   }


    public function actionUpdate($id){
        $model = Feature::findOne($id);
        if($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->saveModel($_POST['Feature']['categories'])) {
                return $this->redirect(['index']);
            }
        }
            return $this->render('form',['model'=>$model]);
    }


    public function actionDelete($id){
        $model = Feature::findOne($id);
        $model->delete();
        if (!Yii::$app->request->isAjax) {
            return $this->redirect(['index']);
        }
    }

    public function actionIndex(){
        $query = Feature::find();
        $query->joinWith(['author'=>function($query){
            $query->from(['author'=>'user']);
        }]);
        $query->addOrderBy([
            'created_at'=>SORT_DESC,
            ]);
        $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination'=>[
                    'pageSize'=>8
                ]
            ]
        );
        $dataProvider->sort->attributes['author.username']=[
            'asc' => ['author.username' => SORT_ASC],
            'desc' => ['author.username' => SORT_DESC],
        ];
        return $this->render('index',[
            'dataProvider'=>$dataProvider,
        ]);
    }
}