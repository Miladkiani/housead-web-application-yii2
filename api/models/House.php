<?php

namespace api\models;


use common\models\User;
use \yii\db\ActiveRecord;
use \yii\helpers\Url;
use Yii;

class House extends \common\models\House
{

    public function fields()
    {
        return [
            'id',
            'title',
            'description',
            'city' => function () {
                return $this->getCityName();
            },
            'neighborhood' => function () {
                return $this->getNeighborhoodName();
            },
            'category' => function () {
                return $this->getCategoryTitle();
            },
            'size',
            'room',
            'furniture',
            'lease_type',
            'prepayment',
            'rent',
            'sell',
            'features',
            'created_at',
            'updated_at',
            'author_name' => function () {
                return $this->getAuthorName();
            },
            'author_phone' => function () {
                return $this->getAuthorPhone();
            },
            'images' => function () {
                return $this->getGallerySrc();
            }
        ];
    }

}