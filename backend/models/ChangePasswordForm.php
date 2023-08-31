<?php


namespace backend\models;


use common\models\User;
use yii\base\Model;

class ChangePasswordForm extends Model
{

    public $old_password;
    public $new_password;
    public $new_password_repeat;

    public function attributeLabels()
    {
     return [
         'old_password'=>\Yii::t('app','old_password'),
         'new_password'=>\Yii::t('app','new_password'),
         'new_password_repeat'=>\Yii::t('app','new_password_repeat'),
     ];
    }

    public function rules()
    {
      return [


          ['old_password','required'],
          ['old_password','checkPassword'],

          ['new_password', 'required'],
          ['new_password', 'string', 'min' => 6],

          ['new_password_repeat','required'],
          ['new_password_repeat','compare','compareAttribute'=>'new_password']
      ];
    }


    public function changePass(){
        if (!$this->validate()){
            return false;
        }
        $id = \Yii::$app->user->identity->getId();
        $user = User::findById($id);
        $user->setPassword($this->new_password);
        $user->save();
        return true;
    }

    public function checkPassword($attribute, $params, $validator)
    {
        $id = \Yii::$app->user->identity->getId();
        $user = User::findById($id);
        if (!$user->validatePassword($this->$attribute)) {
            $this->addError($attribute, \Yii::t('app','wrong_now_password'));
        }
    }


}