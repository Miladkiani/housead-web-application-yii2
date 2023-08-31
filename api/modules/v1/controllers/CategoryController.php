<?php


namespace api\modules\v1\controllers;



use api\models\Category;
use api\models\Feature;
use yii\data\ActiveDataProvider;
use yii\rest\ActiveController;

class CategoryController extends ActiveController
{
 public $modelClass = 'api\models\Category';

 public function actions()
 {
     $actions = parent::actions();
     unset($actions['delete'],$actions['update'],$actions['create'],$actions['view']);
     $actions['index']['prepareDataProvider'] = [$this,'prepareDataProvider'];
     return $actions;
 }

    public function prepareDataProvider(){
        $query = Category::find();
        return new ActiveDataProvider([
            'query'=>$query,
            'pagination'=>false
        ]);
    }


}