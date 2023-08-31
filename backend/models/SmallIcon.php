<?php
namespace backend\models;
use Yii;
use yii\db\ActiveRecord;
/**
 * This is the model class for table "gallery".
 * @property integer $id
 * @property string $image_src_filename
 * @property string $image_web_filename
 * @property integer $notification_id
 */

class SmallIcon extends ActiveRecord
{

    public static function tableName()
    {
        return '{{%small_icon}}';
    }

    public function rules()
    {
        return [
            [['image_src_filename','image_web_filename'],'required'],
            [['image_src_filename','image_web_filename'],'string','max'=>'255'],
            ['image_web_filename','unique', 'targetClass' => '\backend\models\SmallIcon',
                'message' => Yii::t('app','filename_has_already')],
        ];
    }

}