<?php


namespace api\modules\v1\controllers;


use api\models\House;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\rest\ActiveController;

class HouseController extends ActiveController
{
    public $modelClass = 'api\models\House';
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['delete'], $actions['update'], $actions['create'], $actions['view']);
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
        return $actions;
    }

    public function prepareDataProvider()
    {
        $word = \Yii::$app->request->getQueryParam("word");
        $query = House::find();
        $query->andWhere(['status' => House::STATUS_ACTIVE]);
        if (isset($word) && strlen($word) >= 3) {
            $query->andFilterWhere(
                ['or',
                    ['like', 'house.title', $word],
                    ['like', 'house.description', $word]
                ]);
        }
        $query->addOrderBy(['updated_at' => SORT_DESC]);
        return new ActiveDataProvider([
            'query' => $query
        ]);
    }


    public function actionSearch()
    {
        $query = House::find();
        $query->andWhere(['status' => House::STATUS_ACTIVE]);
        $query->addOrderBy(['updated_at' => SORT_DESC]);
        if ($filter = \Yii::$app->request->getBodyParams()) {
            if (isset($filter['category']) && !empty($filter['category'])) {
                $query->andFilterWhere(['in', 'house.category_id', $filter['category']]);
            }
            if (isset($filter['room']) && !empty($filter['room'])) {
                $query->andFilterWhere(['in', 'house.room', $filter['room']]);
            }
            if (isset($filter['feature']) && !empty($filter['feature'])) {
                $query->joinWith('features')->groupBy(['house.id']);
                $query->andFilterWhere(['in', 'feature.id', $filter['feature']]);
            }
            if (isset($filter['neighborhood']) && !empty($filter['neighborhood'])) {
                $query->andFilterWhere(['in', 'house.neighborhood_id', $filter['neighborhood']]);
            }
            if (isset($filter['word'])) {
                $word = $filter['word'];
                if (strlen($word) >= 3) {
                    $query->andFilterWhere(
                        ['or',
                            ['like', 'house.title', $word],
                            ['like', 'house.description', $word]
                        ]);
                }
            }
            if (isset($filter['rent']['gt']) &&
                isset($filter['rent']['lt']) &&
                isset($filter['prepayment']['gt']) &&
                isset($filter['prepayment']['lt']) &&
                isset($filter['sell']['gt']) &&
                isset($filter['sell']['lt'])) {
                $prepayment_gt = $filter['prepayment']['gt'];
                $prepayment_lt = $filter['prepayment']['lt'];
                $rent_gt = $filter['rent']['gt'];
                $rent_lt = $filter['rent']['lt'];
                $sell_gt = $filter['sell']['gt'];
                $sell_lt = $filter['sell']['lt'];

                $query->andFilterWhere(['or',
                    ['between', 'house.prepayment', $prepayment_gt, $prepayment_lt],
                    ['between', 'house.rent', $rent_gt, $rent_lt]
                    , ['between', 'house.sell', $sell_gt, $sell_lt]]);
            }
        }

        return new ActiveDataProvider([
            'query' => $query,
        ]);
    }

    public function actionRange()
    {
        $model = new House();
        return $model->getPriceRange();
    }
}