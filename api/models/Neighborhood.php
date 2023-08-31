<?php


namespace api\models;


class Neighborhood extends \common\models\Neighborhood
{

    public function fields()
    {
        return [
            'id',
            'name'
        ];
    }
}