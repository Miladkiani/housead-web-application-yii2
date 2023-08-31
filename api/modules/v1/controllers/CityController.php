<?php


namespace api\modules\v1\controllers;


use api\models\City;
use yii\data\ActiveDataProvider;
use yii\rest\ActiveController;

class CityController extends ActiveController
{
    public $modelClass = 'api\models\City';

    public function actions()
    {
     $actions = parent::actions();
     unset($actions['delete'],$actions['view'],$actions['update'],$actions['create']);
     $actions['index']['prepareDataProvider'] = [$this,'prepareDataProvider'];
     return $actions;
    }

    public function prepareDataProvider(){
        $query = City::find();
        return new ActiveDataProvider([
            'query'=>$query,
            'pagination'=>false
        ]);
    }

}