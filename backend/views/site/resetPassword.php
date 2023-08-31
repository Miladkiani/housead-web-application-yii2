<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap4\ActiveForm */
/* @var $model \backend\models\ResetPasswordForm */

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

$this->title = Yii::t('app','reset_password');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="main-form login-form">
    <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>
    <section>
        <div class="form-group form-group-1">
            <div class="alert alert-secondary" role = "alert" >
                <span>
                    <?= Yii::t('app','enter_new_password')?>
                </span>
            </div >
            <?= $form->field($model, 'password')->passwordInput(['autofocus' => true]) ?>
            <?= $form->field($model, 'password_repeat')->passwordInput() ?>
            <?= Html::submitButton(Yii::t('app','save'), ['class' => 'btn btn-outline-success submit']) ?>
        </div>
    </section>
    <?php ActiveForm::end(); ?>
</div>
