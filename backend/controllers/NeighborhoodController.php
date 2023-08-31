<?php


namespace backend\controllers;

use backend\utils\Tools;
use common\models\Feature;
use common\models\House;
use common\models\Neighborhood;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\web\Controller;

class NeighborhoodController extends Controller
{
    public function behaviors()
    {
        return [
            'access'=>[
                'class'=>AccessControl::className(),
                'rules'=>[
                    [
                        'actions'=>['index','view','list'],
                        'allow'=>true,
                        'roles'=>['@']
                    ],
                    [
                        'actions'=>['create'],
                        'allow'=>true,
                        'roles'=>['createNeighborhood']
                    ],
                    [
                        'actions'=>['delete'],
                        'allow'=>true,
                        'roles'=>['deleteNeighborhood'],
                        'roleParams' => function() {
                            return ['post' => Neighborhood::findOne(['id' => Yii::$app->request->get('id')])];
                        },
                    ],
                    [
                        'actions'=>['update'],
                        'allow'=>true,
                        'roles' => ['updateNeighborhood'],
                        'roleParams' => function() {
                            return ['post' => Neighborhood::findOne(['id' => Yii::$app->request->get('id')])];
                        },
                    ],
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'list'=>  ['post'],
                ],
            ],
        ];
    }

    public function actionCreate(){
        $model = new Neighborhood();
        if($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->save()) {
                return $this->redirect(['index']);
            }
        }
            return $this->render('form',['model'=>$model]);
    }

    public function actionUpdate($id){
        $model = Neighborhood::findOne($id);
        if($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->save()) {
                return $this->redirect(['index']);
            }
        }
            return $this->render('form',['model'=>$model]);
    }

    public function actionDelete($id){
        if (!Yii::$app->request->isAjax) {
            return $this->redirect(['index']);
        }
        $row = House::find()->where(['neighborhood_id'=>$id])->count();
        if ($row>0){
           return Json::encode(['count'=>Tools::changeToFa($row)]);
        }
        $model = Neighborhood::findOne($id);
        $model->delete();
        return Json::encode(['count'=>0]);
    }

    public function actionIndex(){
        $query = Neighborhood::find();
        $query->joinWith(['author'=>function($query){
            $query->from(['author'=>'user']);
        }]);
        $query->joinWith(['state']);
        $query->addOrderBy(['created_at'=>SORT_DESC]);
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
        $dataProvider->sort->attributes['state.name']=[
            'asc' => ['state.name' => SORT_ASC],
            'desc' => ['state.name' => SORT_DESC],
        ];

        return $this->render('index',[
            'dataProvider'=>$dataProvider,
        ]);
    }
    public function actionList(){
        if (\Yii::$app->request->isAjax) {
            if (!is_null(\Yii::$app->request->post('id'))) {
                $id = \Yii::$app->request->post('id');
                $neighborhoodCounts = Neighborhood::find()
                    ->where(['city_id' => $id])
                    ->count();

                $neighborhoods = Neighborhood::find()
                    ->where(['city_id' => $id])
                    ->all();

                if ($neighborhoodCounts > 0) {
                    foreach ($neighborhoods as $neighborhood) {
                        echo "<option value='" . $neighborhood->id . "'>" . $neighborhood->name . "</option>";
                    }
                } else {
                    echo "<option></option>";
                }
            }
        }
    }
}