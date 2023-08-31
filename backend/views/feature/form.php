<?php

use kartik\form\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

?>
<div class="main-form feature-form">
    <?php
    $this->title =
        ($model->isNewRecord) ? Yii::t('app', 'create_new_feature')
            : Yii::t('app', 'update_feature');
    $this->params['breadcrumbs'][] = [
        'label' => Yii::t('app', 'features'),
        'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;
    $form = ActiveForm::begin();
    ?>
    <section>
        <div class="form-group form-group-1">
            <?php
            echo $form->field($model, 'title')->textInput(
                ['autofocus' => true,
                    'placeholder' => Yii::t('app', 'feature_title_placeholder'),
                    'class' => 'fa-number']);
            ?>
        </div>
    </section>

    <section>
        <div class="form-group form-group-2">
            <?php
            $categoryList =
                ArrayHelper::map(\common\models\Category::find()->all(), 'id', 'title');
            echo $form->field($model, 'categories')->widget(
                Select2::className(), [
                'data' => $categoryList,
                'language' => 'fa',
                'options' => [
                    'placeholder' => Yii::t('app', 'feature_category_placeholder'),
                    'multiple' => 'true'],
            ])->label(Yii::t('app', 'categorize'));
            ?>
        </div>
    </section>
    <?php
    echo Html::submitButton(
        ($model->isNewRecord) ? Yii::t('app', 'create_feature')
            : Yii::t('app', 'save_changes'),
        ['class' => ($model->isNewRecord) ?
            'btn btn-outline-success submit' : 'btn btn-outline-primary submit', 'name' => 'submit'])
    ?>
    <?php ActiveForm::end(); ?>
</div>