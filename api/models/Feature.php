<?php


namespace api\models;


class Feature extends \common\models\Feature
{
    public function fields()
    {
        return [
            'id',
            'title'
        ];
    }
}