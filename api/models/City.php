<?php


namespace api\models;


class City extends \common\models\City
{

    public function fields()
    {
        return [
            'id',
            'name' => function () {
                return $this->getCityStateName();
            }
        ];
    }
}