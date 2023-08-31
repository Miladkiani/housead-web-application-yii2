<?php

use common\models\House;
use kartik\form\ActiveForm;
use backend\models\Admin;
use yii\helpers\Html;
?>

<div class="main-form admin-change-password-form">
    <?php
    $this->title = Yii::t('app','change_password');
    $this->params['breadcrumbs'][] = [
        'label' => Yii::t('app', 'my_profile'),
        'url' => ['view']];
    $this->params['breadcrumbs'][] = $this->title;
    $form = ActiveForm::begin(); ?>
    <section>
        <div class="form-group form-group-1">
            <?php
                echo $form->field($model, 'old_password')->passwordInput();
                echo $form->field($model, 'new_password')->passwordInput();
                echo $form->field($model, 'new_password_repeat')->passwordInput();
            ?>
        </div>
    </section>
            <?php echo \yii\helpers\Html::submitButton(Yii::t('app','change_password'),['class'=>'btn btn-outline-primary submit','name'=>'submit']) ?>
            <?php ActiveForm::end(); ?>
</div>
