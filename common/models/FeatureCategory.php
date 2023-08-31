<?php
namespace common\models;
use yii\db\ActiveRecord;
/**
 * This is the model class for table "feature_category".
 * @property integer $feature_id
 * @property integer $category_id
 **/
class FeatureCategory extends ActiveRecord{
    public static function tableName()
    {
        return '{{%feature_category}}';
    }
}