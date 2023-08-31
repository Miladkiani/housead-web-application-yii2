<?php

use kartik\form\ActiveForm;
use yii\helpers\Html;

?>
<div class="main-form category-form">
    <?php
    $this->title =
        ($model->isNewRecord) ? Yii::t('app', 'create_new_category')
            : Yii::t('app', 'update_category');
    $this->params['breadcrumbs'][] = [
        'label' => Yii::t('app','categories'),
        'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;
    $form = ActiveForm::begin();
    ?>
    <section>
        <div class="form-group form-group-1">
            <?php
            echo $form->field($model, 'title')->textInput(
                    ['autofocus' => true,
                        'placeholder' => Yii::t('app', 'category_title_placeholder'),
                        'class'=>'fa-number']);
            ?>
        </div>
    </section>
            <?php
            echo Html::submitButton(
                    ($model->isNewRecord) ? Yii::t('app', 'create_category')
                        : Yii::t('app', 'save_changes'),
                    ['class' => ($model->isNewRecord) ?
                        'btn btn-outline-success submit' : 'btn btn-outline-primary submit', 'name' => 'submit'])
            ?>
    <?php ActiveForm::end(); ?>
</div>