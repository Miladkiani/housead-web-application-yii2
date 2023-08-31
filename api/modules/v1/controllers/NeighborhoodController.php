<?php


namespace api\modules\v1\controllers;


use api\models\Neighborhood;
use yii\data\ActiveDataProvider;
use yii\rest\ActiveController;

class NeighborhoodController extends ActiveController
{
    public $modelClass = 'api\models\Neighborhood';
    public function actions()
    {
     $actions = parent::actions();
     unset($actions['create'],$actions['delete'],$actions['view'],$actions['update']);
     $actions['index']['prepareDataProvider'] = [$this,'prepareDataProvider'];
     return $actions;
    }

    public function prepareDataProvider(){
        $cityId = \Yii::$app->request->getQueryParam('cityId');
        $query = Neighborhood::find();
        $query->andWhere(['city_id'=>$cityId]);
        $query->addOrderBy(['name'=>SORT_ASC]);
            return new ActiveDataProvider([
               'query'=>$query,
                'pagination'=>false
            ]);
    }


}