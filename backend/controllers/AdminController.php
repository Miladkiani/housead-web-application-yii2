<?php


namespace backend\controllers;
use backend\models\ChangePasswordForm;
use backend\models\UpdateAdminForm;
use backend\models\Admin;
use backend\models\AdminSearch;
use common\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\UploadedFile;

class AdminController extends Controller
{

    public function behaviors()
    {
        return [
            'access'=>
                ['class'=>AccessControl::className(),
                    'rules'=>[
                        [
                            'allow' => true,
                            'actions' =>
                                ['create','update','index'],
                            'roles' => ['chief']
                        ],[
                            'allow'=>true,
                            'actions'=>['view','change-password'],
                            'roles'=>['@']
                            ]
                    ]
                ]
        ];
    }

    public function actionCreate(){
        $model = new Admin();
        $model->scenario = Admin::SCENARIO_SIGNUP;
        if ($model->load(Yii::$app->request->post())) {
            $model->image = UploadedFile::getInstance($model,'image');
            if ($model->signup()){
                return $this->redirect(['index']);
            }
        }
        return $this->render('signup',['model'=>$model]);
    }

    public function actionUpdate($id){
        $admin = User::findone($id);
        $model = new UpdateAdminForm();
        $model->status = $admin->status;
         $role = $admin->role;
         if ($role->item_name == 'chief'){
             $model->role= Admin::CHIEF;
         }else if($role->item_name == 'admin'){
             $model->role= Admin::ADMIN;
         }else if($role->item_name == 'employee'){
             $model->role= Admin::EMPLOYEE;
         }
        if ($model->load(Yii::$app->request->post())) {
            if ($model->checkHasActiveChief($id)){
                if ($model->updateAdmin($id)){
                    return $this->redirect(['index']);
                }
            }else{
             Yii::$app->session->setFlash('error'
                 ,Yii::t('app','least_one_chief'));
            }
        }
        return $this->render('update',['model'=>$model]);
    }


    public function actionIndex(){
        $searchModel = new AdminSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->get());

        return $this->render('index',[
            'dataProvider'=>$dataProvider,
            'searchModel'=>$searchModel,
            'pagination'=>[
                'pageSize'=>5
            ]
        ]);
    }

    public function actionView(){
        $model = $this->findModel();
        $model->scenario= Admin::SCENARIO_PROFILE;
        if (Yii::$app->request->isPost){
            $model->load(Yii::$app->request->post());
            $model->image = UploadedFile::getInstance($model,'image');
            if($model->updateProfile()){
                Yii::$app->session->setFlash('success',
                    Yii::t('app','saved_changes'));
            }
        }
        return $this->render('signup',['model'=>$model]);
    }

    public function actionChangePassword(){
        $model = new ChangePasswordForm();
        if ($model->load(Yii::$app->request->post()) && $model->changePass()){
            Yii::$app->session->setFlash(
                'success',
                Yii::t('app','success_change_pass'));
            return $this->redirect(['view']);
        }
        return $this->render('changePassword',['model'=>$model]);
    }

    protected function findModel(){
        $id = Yii::$app->user->identity->getId();
        $model = Admin::findOne($id);
        return $model;
    }
    
}