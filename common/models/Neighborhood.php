<?php
namespace common\models;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
/**
 * This is the model class for table "neighborhood".
 * @property integer $id
 * @property string $name
 * @property integer $city_id
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $author_id
 * @property User $author
 * @property State $state
 * @property City $city
 */
class Neighborhood extends ActiveRecord
{
  public static function tableName()
  {
      return '{{%neighborhood}}';
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
          'id'=>\Yii::t('app','neighborhood_id'),
          'name'=>\Yii::t('app','neighborhood_name'),
          'city_id'=>\Yii::t('app','city_name'),
          'created_at' => Yii::t('app', 'created_at'),
          'updated_at' => Yii::t('app', 'updated_at'),
          'author_id' => Yii::t('app', 'author_username'),
      ];
  }

  public function rules()
  {

      return [
          ['name','required'],
          ['name','trim'],
          ['name','string','min'=>2,'max'=>30],
          ['city_id','required'],
          ['city_id','integer','message'=>\Yii::t('app','neighborhood_city_empty')]
      ];
  }



    public function getAuthor()
    {
        return $this->hasOne(User::className(), ['id' => 'author_id']);
    }

    public function getCity()
    {
      return $this->hasOne(City::class,['id'=>'city_id']);
    }

    public function getState(){
        return $this->hasOne(State::className(),['id'=>'state_id'])
            ->via('city');
    }



}