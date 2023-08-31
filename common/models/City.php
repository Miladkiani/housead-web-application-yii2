<?php


namespace common\models;

use api\models\State;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "city".
 * @property integer $id
 * @property string $name
 * @property integer $state_id
 * @property State $state
 */
class City extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%city}}';
    }


    public function attributeLabels()
    {
        return [
            'name' => \Yii::t('app', 'city')
        ];
    }

    public function getState()
    {
        return $this->hasOne(State::class, ['id' => 'state_id']);
    }

    public function getCityStateName()
    {

            if (isset($this->state)){
                return  $this->state->name . '،' . $this->name;
            }
    }
}