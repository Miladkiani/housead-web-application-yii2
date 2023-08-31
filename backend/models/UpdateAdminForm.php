<?php


namespace backend\models;


use common\models\User;
use Yii;
use yii\base\Model;
use yii\db\Query;

class UpdateAdminForm extends Model
{

    public $status;
    public $role;

    public function attributeLabels()
    {
        return [
            'status'=>\Yii::t('app','status'),
            'role'=>\Yii::t('app','role'),
        ];
    }

    public function rules()
    {
        return [

            ['status','required'],
            ['status', 'default', 'value' => User::STATUS_ACTIVE],
            ['status', 'in', 'range' => [User::STATUS_ACTIVE, User::STATUS_DELETED,User::STATUS_INACTIVE]],

            ['role', 'required'],
            ['role','in','range'=>[Admin::CHIEF,Admin::ADMIN,Admin::EMPLOYEE]],

        ];
    }

    public function checkHasActiveChief($id)
    {
        $admin = (new Query())
            ->select('id')
            ->from('user')
           ->join('LEFT JOIN', 'auth_assignment', 'user_id = id')
            ->where('id = :id',[':id'=>$id])
            ->andwhere(['item_name'=>'chief']);
        if ($admin->count()>0){
            if ($this->status != User::STATUS_ACTIVE ||
                $this->role != Admin::CHIEF){
                $query = (new Query())
                    ->select('id')
                    ->from('user')
                    ->join('LEFT JOIN', 'auth_assignment', 'user_id = id')
                    ->where('id!=:id',[':id'=>$id])
                    ->andWhere('status = :status', [':status' => User::STATUS_ACTIVE])
                    ->andWhere('item_name = :chief',[':chief'=>'chief']);
                if (!($query->count()>0)){
                   return false;
                }
            }
        }
        return true;
    }

    public function updateAdmin($id){

        if (!$this->validate()){
            return false;
        }

        $user =  User::find()
            ->where(['id'=>$id])
            ->one();
        $user->status = $this->status;
        $user->save();
        $auth = Yii::$app->authManager;
        $auth->revokeAll($user->id);
        if ($this->role == Admin::CHIEF)
        {
            $authRole =  $auth->getRole('chief');
            $auth->assign($authRole,$user->id);
        }else if($this->role == Admin::ADMIN) {
            $authRole =  $auth->getRole('admin');
            $auth->assign($authRole,$user->id);
        }else if($this->role == Admin::EMPLOYEE) {
            $authRole =  $auth->getRole('employee');
            $auth->assign($authRole,$user->id);
        }
        return true;
    }

}