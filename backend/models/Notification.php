<?php


namespace backend\models;
use common\models\User;
use yii\db\ActiveRecord;
use Yii;
use yii\helpers\Html;
use yii\imagine\Image;
use backend\utils\Tools;

/**
 * This is the model class for table "notification".
 * @property integer $id
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $author_id
 * @property integer $time_to_live
 * @property integer $status
 * @property User $author
 * @property string $title
 * @property string $small_body
 * @property string $big_body
 * @property string $color
 * @property LargeIcon $largeIcon;
 * @property SmallIcon $smallIcon;
 */
class Notification extends ActiveRecord
{
    public $small_icon;
    public $large_icon;

    const MAX_SMALL_SIZE = 48;
    const MAX_LARGE_SIZE = 256;
    const ONE_WEEK = 1*60*60*24*7*1;
    const TWO_WEEK = 1*60*60*24*7*2;
    const THREE_WEEK = 1*60*60*24*7*3;
    const FOUR_WEEK = 1*60*60*24*7*4;

    const STATUS_READY = 9;
    const STATUS_SENT = 10;

    public static function tableName()
    {
        return '{{%notification}}';
    }

    public function attributeLabels()
    {
        return [
            'id'=>Yii::t('app','notification_id'),
            'title'=>Yii::t('app','title'),
            'small_body'=>Yii::t('app','small_body'),
            'big_body'=>Yii::t('app','big_body'),
            'small_icon'=>Yii::t('app','small_icon'),
            'large_icon'=>Yii::t('app','large_icon'),
            'time_to_live'=>Yii::t('app','time_to_live'),
            'color'=>Yii::t('app','notification_color'),
            'status'=>Yii::t('app','status'),
            'created_at'=>Yii::t('app','created_at'),
            'updated_at'=>Yii::t('app','updated_at'),
        ];
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

    public function rules()
    {
        return [
            ['id','integer'],
            ['title','required'],
            ['title','trim'],
            ['title','string','min'=>2,'max'=>30],
            ['small_body','required'],
            ['small_body','trim'],
            ['small_body','string','min'=>2,'max'=>150],
            ['big_body','trim'],
            ['big_body','string'],
            ['time_to_live','required'],
            ['time_to_live','number'],
            ['color','trim'],
            ['color','string','min'=>7,'max'=>9],
            ['small_icon', 'image', 'extensions' => 'png, jpeg, jpg, svg',
                'maxSize'=>(100*1024),'maxFiles'=>1,
                'minWidth' => 24 , 'minHeight'=>24 ,
                'maxWidth'=>128,'maxHeight'=>128, ],
            ['large_icon', 'image', 'extensions' => 'png, jpeg, jpg',
                'maxSize'=>(100*1024),'maxFiles'=>1,
                'minWidth' => 128 , 'minHeight'=>128 ,
                'maxWidth'=>256,'maxHeight'=>256, ],
        ];
    }

    public function getAuthor()
    {
        return $this->hasOne(User::className(), ['id' => 'author_id']);
    }

    public function getSmallIcon()
    {
        return $this->hasOne(SmallIcon::className(), ['notification_id' => 'id']);
    }

    public function getLargeIcon()
    {
        return $this->hasOne(LargeIcon::className(), ['notification_id' => 'id']);
    }


    public function saveModel()
    {

        if ($this->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            if (!$this->save()) {
                $transaction->rollBack();
                return false;
            }
            if (!$this->saveLargeIcon()) {
                $transaction->rollBack();
                return false;
            }
            if (!$this->saveSmallIcon()) {
                $transaction->rollBack();
                return false;
            }
            $transaction->commit();
            return true;
        }
        return false;
    }





    private function saveLargeIcon(){
        if (!is_null($this->large_icon)) {
            $this->deleteLargeIcon();
            $largeIcon = new LargeIcon();
            $largeIcon->notification_id = $this->id;
            $largeIcon->image_src_filename = $this->large_icon->name;
            $tmp = explode(".", $this->large_icon->name);
            $ext = end($tmp);
            $largeIcon->image_web_filename = Yii::$app->security->generateRandomString() . ".{$ext}";
            $path = Yii::$app->basePath.Yii::getAlias('@uploadPath').'/'.$largeIcon->image_web_filename;
            if (!$largeIcon->validate() ||  !$largeIcon->save()){
                return false;
            }
            if ($this->large_icon->saveAs($path,true)){
                $originalSize = getimagesize($path);
                $thumbSize = Tools::getThumbSize($originalSize[0],$originalSize[1],self::MAX_LARGE_SIZE);
                Image::thumbnail($path, $thumbSize['thumbWidth'] , $thumbSize['thumbHeight'])->save(
                    Yii::$app->basePath . Yii::getAlias('@largeIconUploadPath') . '/' . $largeIcon->image_web_filename,
                    ['quality' => 100]
                );
                if (isset($path) && file_exists($path)){
                    unlink($path);
                }
            }
        }
        return true;
    }

    private function saveSmallIcon(){
        if (!is_null($this->small_icon)) {
            $this->deleteSmallIcon();
            $smallIcon = new SmallIcon();
            $smallIcon->notification_id = $this->id;
            $smallIcon->image_src_filename = $this->small_icon->name;
            $tmp = explode(".", $this->small_icon->name);
            $ext = end($tmp);
            $smallIcon->image_web_filename = Yii::$app->security->generateRandomString() . ".{$ext}";
            $path = Yii::$app->basePath.Yii::getAlias('@uploadPath').'/'.$smallIcon->image_web_filename;
            if (!$smallIcon->validate() ||  !$smallIcon->save()){
                return false;
            }
            if ($this->small_icon->saveAs($path,true)){
                $originalSize = getimagesize($path);
                $thumbSize = Tools::getThumbSize($originalSize[0],$originalSize[1],self::MAX_SMALL_SIZE);
                Image::thumbnail($path, $thumbSize['thumbWidth'] , $thumbSize['thumbHeight'])->save(
                    Yii::$app->basePath . Yii::getAlias('@smallIconUploadPath') . '/' . $smallIcon->image_web_filename,
                    ['quality' => 100]
                );
                if (isset($path) && file_exists($path)){
                    unlink($path);
                }
            }
        }
        return true;
    }


    public function deleteLargeIcon(){
        $query = LargeIcon::find()->where(['notification_id'=>$this->id]);
        $largeIcon = $query->one();
        if (isset($largeIcon) && !empty($largeIcon)) {
            $largeIcon->delete();
            $path = Yii::$app->basePath
                . Yii::getAlias('@largeIconUploadPath')
                . '/'
                . $largeIcon->image_web_filename;
            if (isset($path) && file_exists($path)) {
                unlink($path);
            }
        }
    }

    public function deleteSmallIcon(){
        $query = SmallIcon::find()->where(['notification_id'=>$this->id]);
        $smallIcon = $query->one();
        if (isset($smallIcon) && !empty($smallIcon)) {
            $smallIcon->delete();
            $path = Yii::$app->basePath
                . Yii::getAlias('@smallIconUploadPath')
                . '/'
                . $smallIcon->image_web_filename;
            if (isset($path) && file_exists($path)) {
                unlink($path);
            }
        }
    }


    public function getSmallIconPath(){
        $path=null;
        if (!$this->isNewRecord) {
            $smallIcon = $this->smallIcon;
            if (isset($smallIcon)) {
                $path = Yii::$app->request->baseUrl .
                    Yii::getAlias('@smallIconUploadUrl') . '/'
                    . $smallIcon->image_web_filename;
            }
        }
        return $path;
    }

    public function getLargeIconPath(){
        $path=null;
        if (!$this->isNewRecord) {
            $largeIcon = $this->largeIcon;
            if (isset($largeIcon)) {
                $path = Yii::$app->request->baseUrl .
                    Yii::getAlias('@largeIconUploadUrl') . '/'
                    . $largeIcon->image_web_filename;
            }
        }
        return $path;
    }
    
    
    public function getSmallIconUrl(){
        $url=null;
        if (!$this->isNewRecord) {
            $smallIcon = $this->smallIcon;
            if (isset($smallIcon)) {
                $url = 
                Yii::$app->request->hostName.Yii::$app->request->baseUrl.Yii::getAlias('@smallIconUploadUrl') . '/'.
                $smallIcon->image_web_filename;
            }
        }
        return $url;
    }
    
     public function getLargeIconUrl(){
         $url=null;
        if (!$this->isNewRecord) {
            $largeIcon = $this->largeIcon;
            if (isset($largeIcon)) {
                $url = 
                Yii::$app->request->hostName.Yii::$app->request->baseUrl. Yii::getAlias('@largeIconUploadUrl') . '/'.
                $largeIcon->image_web_filename;
            }
        }
        return $url;
    }
    
}