<?php
namespace common\models;
use Yii;
use yii\db\ActiveRecord;
/**
 * This is the model class for table "gallery".
 * @property integer $id
 * @property string $image_src_filename
 * @property string $image_web_filename
 * @property integer $house_id
 */

class Gallery extends ActiveRecord
{

 public static function tableName()
 {
     return '{{%gallery}}';
 }

 public function rules()
 {
     return [
       [['image_src_filename','image_web_filename'],'required'],
         [['image_src_filename','image_web_filename'],'string','max'=>'255'],
         ['image_web_filename','unique', 'targetClass' => '\common\models\Gallery',
                'message' => Yii::t('app','filename_has_already')],
     ];
 }

}