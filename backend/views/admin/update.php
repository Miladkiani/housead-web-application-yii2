<?php

use backend\models\Admin;
use kartik\form\ActiveForm;

?>
<div class="main-form admin-update-form">
    <?php
    $this->title =
       Yii::t('app', 'update_user') ;
    $this->params['breadcrumbs'][] = [
        'label' => Yii::t('app', 'users'),
        'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;
    $form = ActiveForm::begin(); ?>

    <section>
        <div class="form-group form-group-1">
            <?php if(Yii::$app->session->hasFlash('error')) {
                echo '<div class="alert alert-danger" role = "alert" >
                <span >'
                    .Yii::$app->session->getFlash('error').'</span>
            </div >';
            }?>
            <?php echo $form->field($model, 'status')
                ->label(Yii::t('app', 'status'))
                ->dropDownList(
                    [
                        \common\models\User::STATUS_ACTIVE=>
                            Yii::t('app','active'),
                        \common\models\User::STATUS_INACTIVE=>
                            Yii::t('app','inactive'),
                        \common\models\User::STATUS_DELETED=>
                            Yii::t('app','deleted'),
                        ]
                    );

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
            ?>
        </div>
    </section>

            <?php echo \yii\helpers\Html::submitButton(
                Yii::t('app', 'save_changes'),
                ['class' => 'btn btn-outline-primary submit', 'name' => 'submit']) ?>
            <?php ActiveForm::end(); ?>
</div>