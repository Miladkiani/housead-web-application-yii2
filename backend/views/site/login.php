<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap4\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use kartik\widgets\ActiveForm;

$this->title = Yii::t('app','admin_panel');
?>
<div class="main-form login-form">
            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
            <section>
                <div class="form-group form-group-1">
                <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

                <?= $form->field($model, 'password')->passwordInput() ?>

                <?= $form->field($model, 'rememberMe')->checkbox() ?>

                <?= Html::a(Yii::t('app','forgot_password'),
                    ['site/request-password-reset'],
                    ['class' => 'forgot-password']) ?>
                <br>
                    <?= Html::submitButton(Yii::t('app','login'),
                ['class' => 'btn btn-outline-success submit', 'name' => 'login-button']) ?>
                </div>
            </section>
            <?php ActiveForm::end(); ?>

</div>
