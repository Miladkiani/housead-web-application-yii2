<?php


namespace common\models;
use yii\db\ActiveRecord;
/**
 * This is the model class for table "state".
 * @property integer $id
 * @property string $name
 * @property City $cities
 */
class State extends ActiveRecord
{
 public static function tableName()
 {
   return '{{%state}}';
 }

 public function attributeLabels()
 {
   return[
       'name'=>\Yii::t('app','state')
   ];
 }

 public function getCities(){
     return $this->hasMany(State::className(),['id','state_id']);
 }



}