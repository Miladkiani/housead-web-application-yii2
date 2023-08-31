<?php

use common\models\House;
use kartik\form\ActiveForm;
use backend\models\Admin;
use yii\helpers\Html;
?>

<div class="main-form admin-signup-form">
    <?php
    $scenario = $model->getScenario();
     if ($scenario == Admin::SCENARIO_SIGNUP){
         $this->params['breadcrumbs'][] = [
             'label' => Yii::t('app','users'),
             'url' => ['index']];
         $this->title =
            Yii::t('app','create_new_user');
            }
    else{
        $this->title =
            Yii::t('app','my_profile');
    }


    $this->params['breadcrumbs'][] = $this->title;
    $form = ActiveForm::begin(
        ['options'=>['enctype'=>'multipart/form-data',]]
    ); ?>
    <section>
        <div class="form-group form-group-1">
            <?php if(Yii::$app->session->hasFlash('success')) {
               echo '<div class="alert alert-success" role = "alert" >
                <span>'.Yii::$app->session->getFlash('success').'</span>
            </div >';
            }?>
            <div class="upload-avatar">
                <?php
                $preview = $model->getProfileImage();
                $path = isset($preview)? Yii::$app->request->baseUrl.
                    Yii::getAlias('@userUploadUrl').'/'
                    .Html::encode($preview):'@web/images/avatar.png';
                echo '<div>';
                    echo Html::img($path,
                        ['alt'=>Yii::t('app','avatar'),
                            'class'=>['avatar']]);
                echo '</div>';
                echo $form->field($model, 'image')
                    ->widget(\kartik\file\FileInput::classname(), [
                        'options' => ['multiple' => false, 'accept' => 'image/*'],
                        'pluginOptions' =>
                            ['allowedFileExtensions' => ['jpg', 'jpeg', 'png'],
                                'showPreview' => false,
                                'showRemove' => false,
                                'showUpload' => false,
                                'showCancel' => false,
                                'showCaption' => false,
                                'maxFileCount' => 1,
                                'minImageWidth' => 196,
                                'minImageHeight' => 196,
                                'maxImageWidth' => 512,
                                'maxImageHeight' => 512,
                                'browseClass' => 'btn btn-success btn-upload',
                                'browseIcon' => '<i class="fa fa-camera"></i> ',
                                'browseLabel' => Yii::t('app', 'select_photo')
                            ],
                    ])->label(''); ?>
            </div>

            <?= $form->field($model, 'first_name')->textInput([
                    'autofocus' => true,
                'class'=>'fa-number']) ?>
            <?= $form->field($model, 'last_name')->textInput([
                'class'=>'fa-number'
            ]) ?>

            <?= $form->field($model, 'username')->textInput([
                    'type'=>'text','maxlength'=>16]) ?>

            <?php
                if($scenario == Admin::SCENARIO_SIGNUP){
                    echo $form->field($model, 'password')->passwordInput();
                    echo $form->field($model, 'password_repeat')->passwordInput();
                }
            ?>
            <?= $form->field($model, 'email')->textInput() ?>
            <?= $form->field($model,'phone')->textInput([
                    'type'=>'text','class'=>'just-number','maxlength'=>11]) ?>
            <?php
                if($scenario == Admin::SCENARIO_SIGNUP) {
                  echo '<div class="alert alert-info" role = "alert" >
                          <p>' .Yii::t('app','chief_role_info').'</p>
                          <p>' .Yii::t('app','admin_role_info').'</p>
                          <p>' .Yii::t('app','employee_role_info').'</p>
                        </div >';
                    echo $form->field($model, 'role')
                        ->label(Yii::t('app', 'role'))
                        ->dropDownList(
                            [
                                \backend\models\Admin::CHIEF=>
                                    Yii::t('app','chief'),
                                \backend\models\Admin::ADMIN=>
                                    Yii::t('app','admin'),
                                \backend\models\Admin::EMPLOYEE=>
                                    Yii::t('app','employee'),
                            ]
                        );
                }
            ?>
            <?php
                if($scenario == Admin::SCENARIO_PROFILE){
                    echo Html::a(Yii::t('app','change_password'),
                        ['admin/change-password'],['class'=>'change_password']
                    );
                }
            ?>
        </div>
    </section>

            <?php echo \yii\helpers\Html::submitButton(
                ($scenario == Admin::SCENARIO_PROFILE)?
                    Yii::t('app','update_user'):
                    Yii::t('app','create_user'),
                ['class'=>($scenario == Admin::SCENARIO_PROFILE)?
                    'btn btn-outline-primary submit':
                    'btn btn-outline-success submit',
                    'name'=>'submit']) ?>

            <?php ActiveForm::end(); ?>
</div>
