<?php


namespace backend\controllers;


use api\models\House;
use backend\utils\Tools;
use common\models\Category;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\web\Controller;

class CategoryController extends Controller
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
                        'roles'=>['createCategory']
                    ],
                    [
                        'actions'=>['delete'],
                        'allow'=>true,
                        'roles'=>['deleteCategory'],
                        'roleParams' => function() {
                            return ['post' => Category::findOne(['id' => Yii::$app->request->get('id')])];
                        },
                    ],
                    [
                        'actions'=>['update'],
                        'allow'=>true,
                        'roles' => ['updateCategory'],
                        'roleParams' => function() {
                            return ['post' => Category::findOne(['id' => Yii::$app->request->get('id')])];
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
        $model = new Category();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->save()) {
                return $this->redirect(['index']);
            }
        }
        return $this->render('form', ['model' => $model]);
    }

    public function actionUpdate($id){
        $model = Category::findOne($id);
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
        $row = House::find()->where(['category_id'=>$id])->count();
        if ($row>0){
            return Json::encode(['count'=>Tools::changeToFa($row)]);
        }
        $model = Category::findOne($id);
        $model->delete();
        return Json::encode(['count'=>0]);
    }

    public function actionIndex(){
        $query = Category::find();
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