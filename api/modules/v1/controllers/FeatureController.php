<?php


namespace api\modules\v1\controllers;


use api\models\City;
use api\models\Feature;
use yii\data\ActiveDataProvider;
use yii\rest\ActiveController;

class FeatureController extends ActiveController
{
    public $modelClass = 'api\models\Feature';
    public function actions()
    {
        $actions = parent::actions();
        unset($actions['delete'],$actions['update'],$actions['create'],$actions['view']);
        $actions['index']['prepareDataProvider'] = [$this,'prepareDataProvider'];
        return $actions;
    }

    public function prepareDataProvider(){
        $query = Feature::find();
        return new ActiveDataProvider([
            'query'=>$query,
            'pagination'=>false
        ]);
    }

}