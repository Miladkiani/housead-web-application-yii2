<?php


namespace api\models;

class Category extends \common\models\Category
{
    public function fields()
    {
        return [
            'id',
            'title',
        ];
    }
}