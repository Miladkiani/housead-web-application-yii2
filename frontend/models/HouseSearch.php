<?php


namespace frontend\models;


use common\models\House;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\Console;

/**
 * This is the model class for table "notification".
 * @property string $token
 * @property integer $min_prepayment
 * @property integer $max_prepayment
 * @property integer $min_rent
 * @property integer $max_rent
 * @property integer $min_sell
 * @property integer $max_sell
 * @property integer $feature_id
 **/
class HouseSearch extends House
{
    public function rules()
    {
        return [
            [
                [
                    'min_sell', 'max_sell',
                    'min_prepayment', 'max_prepayment',
                    'min_rent', 'max_rent'
                ]
                , 'integer'
            ],
            [['category_id', 'room', 'neighborhood_id', 'token', 'lease_type'], 'safe']
        ];
    }

    public function scenarios()
    {
        return Model::scenarios(); // TODO: Change the autogenerated stub
    }

    public function attributes()
    {
        return array_merge(parent::attributes(),
            [
                'min_rent', 'max_rent',
                'min_prepayment', 'max_prepayment',
                'min_sell', 'max_sell', 'token', 'feature_id'
            ]);
    }

    public function search($params)
    {
        $query = House::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 16
            ]
        ]);

        $query->andWhere(['status' => House::STATUS_ACTIVE]);
        $query->addOrderBy(['updated_at' => SORT_DESC]);


        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        // adjust the query by adding the filters
        if (isset($this->lease_type)) {
            $query->andFilterWhere(['house.lease_type' => $this->lease_type]);
            if ($this->lease_type == House::SELL_TYPE) {
                if ($this->min_sell != 0 && $this->max_sell != 0) {
                    $query->andFilterWhere(['between', 'house.sell', $this->min_sell * 10, $this->max_sell * 10]);
                }
            } else if ($this->lease_type == House::RENT_TYPE) {
                if ($this->min_prepayment != 0 && $this->max_prepayment != 0) {
                    $query->andFilterWhere(['between', 'house.prepayment', $this->min_prepayment * 10, $this->max_prepayment * 10]);
                }
                if ($this->min_rent != 0 && $this->max_rent != 0) {
                    $query->andFilterWhere(['between', 'house.rent', $this->min_rent * 10, $this->max_rent * 10]);
                }
            }
        }


        $query->andFilterWhere(['neighborhood_id' => $this->neighborhood_id]);
        $query->andFilterWhere(['category_id' => $this->category_id]);
        $query->andFilterWhere(['room' => $this->room]);
        $query->andFilterWhere(
            ['or',
                ['like', 'house.title', $this->token],
                ['like', 'house.description', $this->token]
            ]);
        return $dataProvider;
    }


}