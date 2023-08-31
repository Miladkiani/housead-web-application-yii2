<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap4\ActiveForm */
/* @var $model \backend\models\PasswordResetRequestForm */

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

$this->title = Yii::t('app','request_reset_password');
$this->params['breadcrumbs'][] =$this->title;
?>
<div class="main-form login-form">
    <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form']); ?>
    <section>
        <div class="form-group form-group-1">
                <?= $form->field($model, 'email')
                    ->textInput(['autofocus' => true]) ?>
                <?= Html::submitButton(Yii::t('app','send'),
                    ['class' => 'btn btn-outline-primary submit']) ?>
        </div>
    </section>
    <?php ActiveForm::end(); ?>
</div>

