<?php


namespace backend\controllers;


use common\models\City;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

class CityController extends Controller
{
    public function behaviors()
    {
        return [
          'access'=>[
              'class'=>AccessControl::className(),
              'rules'=>[
                  [
                      'actions'=>['list'],
                      'allow'=>true,
                      'roles'=>['@']
                  ]
              ]
          ],
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
        if (\Yii::$app->request->isAjax) {
            if (!is_null(\Yii::$app->request->post('id'))) {
                $id = \Yii::$app->request->post('id');
                $cityCounts = City::find()
                    ->where(['state_id' => $id])
                    ->count();

                $cities = City::find()
                    ->where(['state_id' => $id])
                    ->all();

                //return json_encode($cities);
                if ($cityCounts > 0) {
                    echo "<option>انتخاب شهر...</option>";
                    foreach ($cities as $city) {

                        echo "<option value='" . $city->id . "'>" . $city->name . "</option>";
                    }
                } else {
                    echo "<option></option>";
                }
            }
        }
    }
}