<?php

use kartik\form\ActiveForm;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\View;

?>
<?php
$this->registerJs("$('#neighborhood-state').change(function(){
        $('#house-city').empty();
          $('#house-neighborhood_id').empty();
        var stateId = $(this).val();
        $.ajax({
            url: '" . Url::to(['/city/list']) . "',
            type: 'post',
            data: {id:stateId},
            dataType: 'text',
            success:function(response){
                $('#neighborhood-city_id').html(response);
            }
        });
    });", View::POS_READY);
?>
<div class="main-form neighborhood-form">
    <?php
    $this->title =
        ($model->isNewRecord) ? Yii::t('app', 'create_new_neighborhood') :
            Yii::t('app', 'update_neighborhood');
    $this->params['breadcrumbs'][] = [
        'label' => Yii::t('app','neighborhoods'),
        'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;
    $form = ActiveForm::begin(); ?>

    <section>
        <div class="form-group form-group-1">
            <?php echo $form->field($model, 'state')
                ->label(
                    Yii::t('app', 'state')
                )->dropDownList(\yii\helpers\ArrayHelper::map(
                    \common\models\State::find()
                        ->orderBy('name')
                        ->all(), 'id', 'name'),
                    ['prompt' => Yii::t('app', 'neighborhood_state_prompt'),
                    ]);

            $cities = \common\models\City::find()
                ->where(['state_id' => ($model->isNewRecord) ?
                    null : (isset($model->state) ? $model->state->id : null)])
                ->orderBy('name')
                ->all();

            echo $form->field($model, 'city_id')
                ->label(Yii::t('app', 'city'))
                ->dropDownList(\yii\helpers\ArrayHelper::map($cities, 'id', 'name'),
                    ['prompt' => Yii::t('app', 'neighborhood_city_prompt')]
                );

            echo $form->field($model, 'name')->textInput([
                'placeholder' =>
                    Yii::t('app', 'neighborhood_name_placeholder'),
                'class'=>'fa-number']);
            ?>
        </div>
    </section>

            <?php echo \yii\helpers\Html::submitButton(($model->isNewRecord) ?
                Yii::t('app', 'create_neighborhood') :
                Yii::t('app', 'save_changes'),
                ['class' => $model->isNewRecord ? 'btn btn-outline-success submit' : 'btn btn-outline-primary submit', 'name' => 'submit']) ?>
            <?php ActiveForm::end(); ?>
</div>