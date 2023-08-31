<?php

namespace common\models;

use backend\utils\Tools;
use \yii\helpers\Url;
use Yii;
use yii\db\ActiveRecord;
use yii\db\Query;
use yii\imagine\Image;
use yii\web\UploadedFile;

/**
 * This is the model class for table "house".
 *
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property integer $category_id
 * @property integer $neighborhood_id
 * @property string $address
 * @property string $postcode
 * @property string $phone
 * @property integer $size
 * @property integer $room
 * @property boolean $furniture
 * @property integer $lease_type
 * @property integer $sell
 * @property integer $prepayment
 * @property integer $rent
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $author_id
 * @property integer $status
 * @property User $author
 * @property City $city
 * @property Neighborhood $neighborhood
 * @property State $state
 * @property Category $category
 * @property Gallery[] $images
 * @property Feature[] $features
 **/
class House extends ActiveRecord
{
    const STATUS_INACTIVE = 9;
    const STATUS_ACTIVE = 10;

    const MAX_SIZE = 800;
    const MAX_THUMB_SIZE = 300;
    const RENT_TYPE = 33;
    const SELL_TYPE = 22;
    /**
     * @var UploadedFile[]
     */
    public $images;


    public static function tableName()
    {
        return '{{%house}}';
    }

    public function beforeValidate()
    {
        if (parent::beforeValidate()) {
            $this->sell = str_replace(',', '', $this->sell);
            $this->prepayment = str_replace(',', '', $this->prepayment);
            $this->rent = str_replace(',', '', $this->rent);

            return true;
        }
        return false;
    }

    public function afterFind()
    {
        parent::afterFind();
        if ($this->lease_type == self::SELL_TYPE) {
            $this->sell = intval($this->sell) / 10;
        } else {
            $this->prepayment = intval($this->prepayment) / 10;
            $this->rent = intval($this->rent) / 10;
        }
        return true;
    }


    public function beforeSave($insert)
    {

        if (parent::beforeSave($insert)) {

            if ($this->isNewRecord) {
                // if it is new record save the current timestamp as created time
                $this->created_at = time();
                $this->author_id = Yii::$app->user->id;
            }
            if ($this->lease_type == self::SELL_TYPE) {
                $this->sell = intval($this->sell) * 10;
            } else {
                $this->prepayment = intval($this->prepayment) * 10;
                $this->rent = intval($this->rent) * 10;
            }
            $this->updated_at = time();
            return true;
        }
        return false;

    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'house_id'),
            'title' => Yii::t('app', 'house_title'),
            'description' => Yii::t('app', 'description'),
            'category_id' => Yii::t('app', 'categorize'),
            'neighborhood_id' => Yii::t('app', 'neighborhood_name'),
            'address' => Yii::t('app', 'address'),
            'postcode' => Yii::t('app', 'postcode'),
            'phone' => Yii::t('app', 'house_phone'),
            'size' => Yii::t('app', 'house_size'),
            'room' => Yii::t('app', 'house_room'),
            'furniture' => Yii::t('app', 'furniture'),
            'lease_type' => Yii::t('app', 'lease_type'),
            'sell' => Yii::t('app', 'sell_price'),
            'rent' => Yii::t('app', 'rent_price'),
            'prepayment' => Yii::t('app', 'prepayment'),
            'images' => Yii::t('app', 'house_images'),
            'created_at' => Yii::t('app', 'created_at'),
            'updated_at' => Yii::t('app', 'updated_at'),
            'author_id' => Yii::t('app', 'author_username'),
            'status' => Yii::t('app', 'status'),
        ];
    }

    public function rules()
    {
        return [

            [['title', 'category_id', 'address', 'phone', 'neighborhood_id',
                'size', 'lease_type'], 'required'],
            ['title', 'string', 'min' => 5, 'max' => 50],
            [['postcode', 'phone'], 'string', 'max' => 11],
            [['description', 'address'], 'string'],
            [['title', 'address', 'description'], 'trim'],
            [['size', 'room'], 'number'],
            [['room', 'furniture'], 'default', 'value' => 0],

            ['images', 'image', 'extensions' => 'png, jpeg, jpg',
                'minWidth' => 800, 'minHeight' => 400, 'maxFiles' => 4, 'maxSize' => 500 * 1024],
            ['images', 'hasImage', 'skipOnEmpty' => false, 'skipOnError' => false],

            ['status', 'default', 'value' => self::STATUS_INACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE]],

            ['sell', 'required', 'when' => function ($model) {
                if ($model->lease_type == House::SELL_TYPE) {
                    return true;
                } else {
                    return false;
                }
            }, 'whenClient' => "function (attribute, value) {
              var inputValue =  $('input[name=\"house[lease_type]\"]:checked').attr(\"value\");
              if (inputValue == 22){
                return true;
              } else{
                return false;
              }
         }"],
            ['rent', 'required', 'when' => function ($model) {
                if ($model->lease_type == House::RENT_TYPE) {
                    return true;
                } else {
                    return false;
                }
            }, 'whenClient' => "function (attribute, value) {
              var inputValue =  $('input[name=\"house[lease_type]\"]:checked').attr(\"value\");
              if (inputValue == 33){
                return true;
              }else{
                return false;
              }
         }"],
            ['prepayment', 'required', 'when' => function ($model) {
                if ($model->lease_type == House::RENT_TYPE) {
                    return true;
                } else {
                    return false;
                }
            }, 'whenClient' => "function (attribute, value) {
              var inputValue =  $('input[name=\"house[lease_type]\"]:checked').attr(\"value\");
              if (inputValue == 33){
                return true;
              } else{
                return false;
              }
         }"]
        ];
    }


    public function hasImage($attribute)
    {
        if (is_null($this->$attribute) || empty($this->$attribute)) {
            if ($this->isNewRecord) {
                $this->addError($attribute, \Yii::t('app', 'house_image_empty'));
            } else {
                $gallery = $this->gallery;
                if (is_null($gallery) || empty($gallery)) {
                    $this->addError($attribute, \Yii::t('app', 'house_image_empty'));
                }
            }
        }

    }

    public function getGallery()
    {
        return $this->hasMany(Gallery::className(), ['house_id' => 'id']);
    }

    public function getFeatures()
    {
        return $this->hasMany(Feature::className(), ['id' => 'feature_id'])
            ->viaTable('house_feature', ['house_id' => 'id']);
    }

    public function getNeighborhood()
    {
        return $this->hasOne(Neighborhood::className(), ['id' => 'neighborhood_id']);
    }


    public function getCity()
    {
        return $this->hasOne(City::className(), ['id' => 'city_id'])
            ->via('neighborhood');
    }

    public function getState()
    {
        return $this->hasOne(State::className(), ['id' => 'state_id'])
            ->via('city');
    }

    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    public function getAuthor()
    {
        return $this->hasOne(User::className(), ['id' => 'author_id']);
    }

    public function saveModel($features)
    {

        if ($this->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            if (!$this->save()) {
                $transaction->rollBack();
                return false;
            }
            if (!$this->saveFeatures($features)) {
                $transaction->rollBack();
                return false;
            }
            if (!$this->saveImages()) {
                $transaction->rollBack();
                return false;
            }
            $transaction->commit();
            return true;
        }
        return false;
    }

    private function saveFeatures($features)
    {
        if (!($this->isNewRecord)) {
            HouseFeature::deleteAll(['house_id' => $this->id]);
        }
        if (isset($features) && !empty($features)) {
            foreach ($features as $id) {
                $featureHouse = new HouseFeature();
                $featureHouse->house_id = $this->id;
                $featureHouse->feature_id = $id;
                $featureHouse->save();
            }
        }
        return true;
    }

    private function saveImages()
    {
        if (!is_null($this->images) && !empty($this->images)) {
            foreach ($this->images as $image) {
                $imageTbl = new Gallery();
                $imageTbl->house_id = $this->id;
                $imageTbl->image_src_filename = $image->name;
                $tmp = explode(".", $image->name);
                $ext = end($tmp);
                $imageTbl->image_web_filename = Yii::$app->security->generateRandomString() . ".{$ext}";
                $path = Yii::$app->basePath . Yii::getAlias('@uploadPath') . '/' . $imageTbl->image_web_filename;
                if (!$imageTbl->validate() || !$imageTbl->save()) {
                    return false;
                }
                if ($image->saveAs($path)) {
                    $originalSize = getimagesize($path);
                    $thumbSize = Tools::getThumbSize($originalSize[0], $originalSize[1], self::MAX_THUMB_SIZE);
                    Image::thumbnail($path, $thumbSize['thumbWidth'], $thumbSize['thumbHeight'])->save(
                        Yii::$app->basePath . Yii::getAlias('@houseThumbUploadPath') . '/' . $imageTbl->image_web_filename,
                        ['quality' => 100]
                    );
                    $normalSize = Tools::getThumbSize($originalSize[0], $originalSize[1], self::MAX_SIZE);
                    Image::thumbnail($path, $normalSize['thumbWidth'], $normalSize['thumbHeight'])->save(
                        Yii::$app->basePath . Yii::getAlias('@houseUploadPath') . '/' . $imageTbl->image_web_filename,
                        ['quality' => 100]
                    );
                    if (isset($path) && file_exists($path)) {
                        unlink($path);
                    }
                }
            }
        }
        return true;
    }


    public function getAuthorPhone()
    {
        return $this->author->phone;
    }

    public function getCategoryTitle()
    {
        return $this->category->title;
    }

    public function getAuthorName()
    {
        return $this->author->first_name . ' ' . $this->author->last_name;
    }

    public function getNeighborhoodName()
    {
        return $this->neighborhood->name;
    }

    public function getCityName()
    {
        return $this->city->name;
    }

    public function getGallerySrc()
    {
        $images = [];
        if (isset($this->gallery) && !empty($this->gallery)) {
            foreach ($this->gallery as $gallery) {
                $array['original'] = Url::to('@houseUploadUrl', true) . '/' .
                    $gallery->image_web_filename;
                $array['thumbnail'] = Url::to('@houseThumbUploadUrl', true) . '/' .
                    $gallery->image_web_filename;
                $array['id']= $gallery->id;
                $array['filename']= $gallery->image_src_filename;
                $images[] = $array;
            }
        }
        return $images;
    }

    public function getMainImage()
    {
        $mainImageUrl = "";
        $images = $this->getGallery();
        if (isset($images) && !empty($images)) {
            $mainImage = $images->one();
            $mainImageUrl = \yii\helpers\Url::to('@houseThumbUploadUrl', true) . '/' .
                $mainImage->image_web_filename;
        }
        return $mainImageUrl;
    }

   public function getPriceRange(){
       $sql = "min(rent) as min_rent,
         max(rent) as max_rent,
         min(prepayment) as min_prepayment,
         max(prepayment) as max_prepayment,
         min(sell) as min_sell,
         max(sell) as max_sell";
       $query = (new Query())->select($sql)
           ->from('house')->limit(1)
           ->Where(['status' => House::STATUS_ACTIVE])
           ->one();
       $query['min_rent'] = $query['min_rent'] / 10 ;
       $query['max_rent'] = $query['max_rent'] / 10 ;
       $query['min_sell'] = $query['min_sell'] / 10 ;
       $query['max_sell'] = $query['max_sell'] / 10 ;
       $query['min_prepayment'] = $query['min_prepayment'] / 10 ;
       $query['max_prepayment'] = $query['max_prepayment'] / 10 ;
       return $query;
   }

}