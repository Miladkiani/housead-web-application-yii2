<?php
namespace backend\models;

use common\models\Avatar;
use Yii;
use common\models\User;
use yii\imagine\Image;
use backend\utils\Tools;

/**
 * Signup form
 */
class Admin extends User
{

    public $image;
    public $password;
    public $password_repeat;
    public $role;
    const CHIEF = 100;
    const ADMIN = 90;
    const EMPLOYEE = 80;
    const SCENARIO_SIGNUP = "register";
    const SCENARIO_PROFILE = "update";

    const MAX_THUMB_SIZE = 300;

    public function scenarios()
    {
     $scenarios = parent::scenarios();
     $scenarios[self::SCENARIO_SIGNUP]= ['username','password','password_repeat','email',
         'phone','role','image','first_name','last_name'];
        $scenarios[self::SCENARIO_PROFILE]= ['username','email',
            'phone','image','first_name','last_name'];

         return $scenarios;
    }

    public function  attributeLabels()
     {
         $labels = parent::attributeLabels();
         $labels['password']=Yii::t('app','password');
         $labels['password_repeat']=Yii::t('app','password_repeat');
         return $labels;
     }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        $parentRules = parent::rules();
        $newRules =   [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\backend\models\Admin',
                'message' => Yii::t('app','username_already_exist')],
            ['username', 'string', 'min' => 3, 'max' => 16],
            ['username','match','pattern'=>'/^[a-zA-Z][0-9a-zA-Z\_]+$/',
                'message'=>Yii::t('app','username_pattern')],

            ['first_name', 'trim'],
            ['first_name', 'required'],
            ['first_name', 'string', 'min' => 2, 'max' => 25],

            ['last_name', 'trim'],
            ['last_name', 'required'],
            ['last_name', 'string', 'min' => 2, 'max' => 25],

            ['image', 'image', 'extensions' => 'png, jpeg, jpg',
                'maxSize'=>(500*1024),'maxFiles'=>1,
                'minWidth' => 196 , 'minHeight'=>196 ,
                'maxWidth'=>512,'maxHeight'=>512, ],

            ['phone','required'],
            ['phone','string','max'=>11],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\backend\models\Admin',
                'message' => Yii::t('app','email_already_exist')],

            ['role','required'],
            ['role','in','range'=>[self::CHIEF,self::ADMIN,self::EMPLOYEE]],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],

            ['password_repeat','required'],
            ['password_repeat','compare','compareAttribute'=>'password']
        ];
        $rules = array_merge($parentRules,$newRules);
        return $rules;
    }



    public function signup()
    {
        if (!$this->validate()) {
                return  null;
        }
        $this->setPassword($this->password);
        $this->generateAuthKey();
        $this->save(false);
        $this->saveAvatar();
        $auth = Yii::$app->authManager;
        if ($this->role == self::CHIEF)
        {
           $authRole =  $auth->getRole('chief');
            $auth->assign($authRole,$this->id);
        }else if($this->role == self::ADMIN) {
            $authRole =  $auth->getRole('admin');
            $auth->assign($authRole,$this->id);
        }else if($this->role == self::EMPLOYEE) {
            $authRole =  $auth->getRole('employee');
            $auth->assign($authRole,$this->id);
        }
        return true;
    }

    private function deleteAvatar(){
        $query = Avatar::find()->where(['user_id'=>$this->id]);
        $image = $query->one();
        if (isset($image) && !empty($image)) {
            $image->delete();
            $path = Yii::$app->basePath
                . Yii::getAlias('@userUploadPath')
                . '/'
                . $image->image_web_filename;
            if (isset($path) && file_exists($path)) {
                unlink($path);
            }
        }
    }

    private function saveAvatar(){
        if (!is_null($this->image)) {
            $this->deleteAvatar();
            $avatar = new Avatar();
            $avatar->user_id = $this->id;
            $avatar->image_src_filename = $this->image->name;
            $tmp = explode(".", $this->image->name);
            $ext = end($tmp);
            $avatar->image_web_filename = Yii::$app->security->generateRandomString() . ".{$ext}";
            $path = Yii::$app->basePath.Yii::getAlias('@uploadPath').'/'.$avatar->image_web_filename;
            if (!$avatar->validate() ||  !$avatar->save()){
                return false;
            }
            if ($this->image->saveAs($path,true)){
                $originalSize = getimagesize($path);
                $thumbSize = Tools::getThumbSize($originalSize[0],$originalSize[1],self::MAX_THUMB_SIZE);
                Image::thumbnail($path, $thumbSize['thumbWidth'] , $thumbSize['thumbHeight'])->save(
                    Yii::$app->basePath . Yii::getAlias('@userUploadPath') . '/' . $avatar->image_web_filename,
                    ['quality' => 100]
                );
                if (isset($path) && file_exists($path)){
                    unlink($path);
                }
            }
        }

        return true;
    }

    public function updateProfile(){

        if (!$this->validate()) {
            return  null;
        }
        $this->save(false);
        $this->saveAvatar();
        return true;
    }

    public function getProfileImage(){
        if (!$this->isNewRecord) {
            $avatar = $this->avatar;
            if (isset($avatar)) {
                return $avatar->image_web_filename;
            }
        }
        return null;
    }

}
