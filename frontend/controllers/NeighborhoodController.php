<?php


namespace frontend\controllers;


use common\models\Neighborhood;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\web\Controller;

class NeighborhoodController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'list' => ['post'],
                ],
            ],
        ];
    }


    public function actionList()
    {
        if (\Yii::$app->request->isAjax && isset($_POST['id'])) {
            $id = \Yii::$app->request->post('id');
            $neighborhoodCounts = Neighborhood::find()
                ->where(['city_id' => $id])
                ->count();
            $neighborhoods = Neighborhood::find()
                ->where(['city_id' => $id])
                ->all();
            if ($neighborhoodCounts > 0) {
                return Json::encode($neighborhoods);
            }
        }
    }
}