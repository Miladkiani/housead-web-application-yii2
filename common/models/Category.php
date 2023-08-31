<?php


namespace common\models;


use Yii;
use yii\db\ActiveRecord;
/**
 * This is the model class for table "category".
 * @property integer $id
 * @property string $title
 */
class Category extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%category}}';
    }

    public function beforeSave($insert)
    {

        if (parent::beforeSave($insert)) {

            if ($this->isNewRecord) {
                // if it is new record save the current timestamp as created time
                $this->created_at = time();
                $this->author_id = Yii::$app->user->id;
            }

            $this->updated_at = time();

            return true;
        }
        return false;

    }

    public function attributeLabels()
    {
        return [
            'id'=>\Yii::t('app','category_id'),
            'title'=>\Yii::t('app','category_title'),
            'created_at' => Yii::t('app', 'created_at'),
            'updated_at' => Yii::t('app', 'updated_at'),
            'author_id' => Yii::t('app', 'author_username'),
        ];
    }

    public function rules()
    {
        return [
            ['id','integer'],
            ['title','trim'],
            ['title','required'],
            ['title', 'unique', 'targetClass' => '\common\models\Category',
                'message' => Yii::t('app','category_title_already_exist')],
            ['title','string','min'=>2,'max'=>30],
        ];
    }

    public function getAuthor()
    {
        return $this->hasOne(User::className(), ['id' => 'author_id']);
    }
}