<?php
namespace common\models;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;


/**
 * This is the model class for table "feature".
 * @property integer $id
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $author_id
 * @property User $author
 * @property string $title
 * @property Category[]  $categories;
 */
class Feature extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%feature}}';
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
            'id'=>\Yii::t('app','feature_id'),
            'title'=>\Yii::t('app','feature_title'),
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
            ['title', 'unique', 'targetClass' => '\common\models\Feature',
                'message' => Yii::t('app','feature_title_already_exist')],
            ['title','string','min'=>2,'max'=>30],
        ];
    }


    public function getAuthor()
    {
        return $this->hasOne(User::className(), ['id' => 'author_id']);
    }


    public function getCategories(){
        return $this->hasMany(Category::className(),['id'=>'category_id'])
            ->viaTable('feature_category',['feature_id'=>'id']);
    }

    public function saveModel($categories)
    {
        if ($this->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            if (!$this->save()) {
                $transaction->rollBack();
                return false;
            }
            if (!$this->saveCategories($categories)) {
                $transaction->rollBack();
                return false;
            }
            $transaction->commit();
            return true;
        }
        return false;
    }





    private function saveCategories($categories){
        if(!$this->isNewRecord){
            FeatureCategory::deleteAll(['feature_id' =>$this->id]);
        }
        if(isset($categories) && !empty($categories)){
            foreach ($categories as $id){
                $featureCategory = new FeatureCategory();
                $featureCategory->feature_id = $this->id;
                $featureCategory->category_id = $id;
                if (!($featureCategory->save())){
                 return false;
                }
            }
            return true;
        }
        return false;
    }


}