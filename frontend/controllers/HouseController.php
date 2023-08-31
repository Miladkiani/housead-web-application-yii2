<?php

namespace frontend\controllers;

use common\models\House;
use frontend\models\HouseSearch;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Response;
use Yii;

class HouseController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'range' => ['get']
                ],
            ],
        ];
    }


    public function actionIndex()
    {
        $searchModel = new HouseSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->post());
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }


    public function actionView($id)
    {
        $model = House::findOne($id);
        return $this->render('detail', ['model' => $model]);
    }

    public function actionRange()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $model = new House();
            $result = $model->getPriceRange();
            return $result;
        }
    }
}