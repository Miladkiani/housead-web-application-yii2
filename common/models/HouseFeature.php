<?php
namespace common\models;
use yii\db\ActiveRecord;
/**
 * This is the model class for table "house_feature".
 * @property integer $feature_id
 * @property integer $house_id
 */
class HouseFeature extends ActiveRecord{
    public static function tableName()
    {
        return '{{%house_feature}}';
    }
}