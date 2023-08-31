<?php

use common\models\Category;
use common\models\City;
use common\models\Feature;
use common\models\Neighborhood;
use common\models\State;
use kartik\form\ActiveField;
use kartik\form\ActiveForm;
use kartik\widgets\Select2;
use kartik\widgets\SwitchInput;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use common\models\House;
use kartik\file\FileInput;
use yii\helpers\Url;
use yii\web\View;

$this->registerJs("$('#house-state').change(function(){
          $('neighborhood-city_id').empty();
        let stateId = $(this).val();
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

$this->registerJs("$('#house-city').change(function(){
        $('#neighborhood-neighborhood_id').empty();
        var cityId = $(this).val();
        $.ajax({
            url: '" . Url::to(['/neighborhood/list']) . "',
            type: 'post',
            data: {id:cityId},
            dataType: 'text',
            success:function(response){
                $('#house-neighborhood_id').html(response);
            }
        });
    });", View::POS_READY);
?>


<div class="main-form house-form">
    <?php
    $this->title =
        $model->isNewRecord ? Yii::t('app', 'create_new_house') :
            Yii::t('app', 'update_house');
    $this->params['breadcrumbs'][] = [
        'label' => Yii::t('app', 'houses'),
        'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;

    $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data',]]);
    ?>
    <section>
        <h5 class="form-group-header"><?php echo Yii::t('app', 'house_title_description') ?></h5>
        <div class="form-group form-group-1">
            <?php
            echo $form->field($model, 'title')->textInput([
                'autofocus' => true,
                'placeholder' => Yii::t('app', 'house_title_placeholder'),
                'class' => 'fa-number'
            ]);
            echo $form->field($model, 'description')->textarea(
                [
                    'placeholder' => Yii::t('app', 'house_description_placeholder'),
                    'rows' => '3',
                    'class' => 'fa-number']);
            echo $form->field($model, 'category_id')->dropDownList(
                ArrayHelper::map(
                    Category::find()->all(), 'id', 'title')
            );
            ?>
    </section>
    <section>
        <h5 class="form-group-header">
            <?php echo Yii::t('app', 'house_address') ?>
        </h5>
        <div class="form-group form-group-2">
            <?php echo $form->field($model, 'state')
                ->label(Yii::t('app', 'state'))
                ->dropDownList(
                    ArrayHelper::map(
                        State::find()
                            ->orderBy('name')
                            ->all(),
                        'id', 'name'),
                    ['prompt' => Yii::t('app', 'house_state_prompt'),]
                );

            $cities = City::find()
                ->where(['state_id' =>
                    ($model->isNewRecord) ? null : (isset($model->state) ? $model->state->id : null)])
                ->orderBy('name')
                ->all();

            echo $form->field($model, 'city')
                ->label(Yii::t('app', 'city'))
                ->dropDownList(ArrayHelper::map($cities, 'id', 'name'),
                    ['prompt' => Yii::t('app', 'house_city_prompt')]
                );

            $neighborhoods = Neighborhood::find()
                ->where(['city_id' =>
                    ($model->isNewRecord) ? null : (isset($model->city) ? $model->city->id : null)])
                ->orderBy('name')
                ->all();

            echo $form->field($model, 'neighborhood_id')->dropDownList(
                ArrayHelper::map($neighborhoods, 'id', 'name'),
                ['prompt' => Yii::t('app', 'house_neighborhood_prompt')]
            );

            echo $form->field($model, 'address', [
                    'hintType' => ActiveField::HINT_SPECIAL,
                    'hintSettings' =>
                        ['placement' => 'right', 'onLabelClick' => true, 'onLabelHover' => false]
                ]
            )->textarea([
                'placeholder' => Yii::t('app', 'house_address_placeholder'),
                'rows' => '3',
                'class' => 'fa-number']);

            echo $form->field($model, 'postcode')
                ->textInput(['type' => 'text', 'maxlength' => 11, 'class' => 'center-input fa-number ']);

            echo $form->field($model, 'phone')->textInput([
                'type' => 'text', 'class' => 'phone center-input just-number', 'maxlength' => 11]);
            ?>
    </section>

    <section>

        <h5 class="form-group-header">
            <?php echo Yii::t('app', 'house_features') ?>
        </h5>

        <div class="form-group form-group-3">
            <section>
                <?= $form->field($model, 'size'
                )->textInput(['placeholder' => Yii::t('app', 'house_size_placeholder'),
                    'class' => 'center-input fa-number just-number']); ?>

                <?= $form->field($model, 'room')
                    ->textInput([
                        'placeholder' =>
                            Yii::t('app', 'house_room_placeholder'),
                        'class' => 'center-input fa-number just-number']); ?>

                <?= $form->field($model, 'furniture')
                    ->widget(SwitchInput::className(), [
                        'value' => ($model->furniture == 1) ? true : false,
                        'pluginOptions' =>
                            ['size' => 'medium',
                                'onText' => Yii::t('app', 'is'),
                                'offText' => Yii::t('app', 'isnt')],
                    ]);
                ?>
            </section>
            <section>
                <?php
                $featureList = ArrayHelper::map(Feature::find()->all(), 'id', 'title');
                echo $form->field($model, 'features')->widget(
                    Select2::className(), [
                    'data' => $featureList,
                    'options' => [
                        'placeholder' =>
                            Yii::t('app', 'house_features_placeholder'),
                        'multiple' => 'true',
                    ],

                ])->label(Yii::t('app', 'features'));
                ?>
            </section>
        </div>
    </section>
    <section>
        <h5 class="form-group-header"><?php echo Yii::t('app', 'house_images') ?></h5>
        <div class="form-group form-group-4">
            <?php

            $initialPreview = array();
            $initialPreviewConfig = array();
            $gallery = $model->getGallerySrc();
            if (isset($gallery) && !empty($gallery)) {
                foreach ($gallery as $image) {
                    $initialPreview[] =
                        '<img src="' .$image['thumbnail']. '" width="100%" height="100%" class="file-preview-image">';
                    $initialPreviewConfig[] = ['caption' => $image['filename'],
                        'url' => Url::to('@web/gallery/delete'), 'key' => $image['id']];
                }

            }
            echo $form->field($model, 'images[]')->widget(FileInput::classname(), [
                'options' => ['multiple' => true, 'accept' => 'image/*'],
                'pluginOptions' =>
                    ['allowedFileExtensions' => ['jpg', 'jpeg', 'png'],
                        'overwriteInitial' => false,
                        'initialPreview' => $initialPreview,
                        'initialPreviewConfig' => $initialPreviewConfig,
                        'showRemove' => false,
                        'showUpload' => false,
                        'showCancel' => false,
                        'maxFileSize' => 500,
                        'maxFileCount' => 4,
                        'minImageWidth' => 800,
                        'minImageHeight' => 400,
                    ],
            ])->label(false); ?>
        </div>
    </section>
    <section>
        <h5 class="form-group-header">
            <?php echo Yii::t('app', 'house_lease') ?>
        </h5>
        <div class="form-group form-group-5">
            <?php
            echo $form->field($model, 'lease_type')->radioList(
                array(house::SELL_TYPE => Yii::t('app', 'sell'),
                    house::RENT_TYPE => Yii::t('app', 'rent')),
                [
                    'item' => function ($index, $label, $name, $checked, $value) {
                        $checked = ($checked) ? 'checked' : '';
                        $return = '<label class="radio-inline">';
                        $return .= '<input type="radio"  name="' . $name . '" value="' . $value .
                            '" ' . $checked . ' >';
                        $return .= '<i></i>';
                        $return .= '<span>' . ucwords($label) . '</span>';
                        $return .= '</label>';
                        return $return;
                    }
                ]
            );
            echo "<div class='reveal-if reveal-sell-if " . house::SELL_TYPE . "'>";
            echo $form->field($model, 'sell')->textInput(
                ['class' => 'center-input fa-number just-number price ' . house::SELL_TYPE]);
            echo "</div>";
            echo "<div class='reveal-if reveal-rent-if " . house::RENT_TYPE . "'>";
            echo $form->field($model, 'prepayment')->textInput(
                ['class' => 'center-input fa-number just-number price ' . house::RENT_TYPE]);
            echo $form->field($model, 'rent')->textInput(
                ['class' => 'center-input fa-number just-number price ' . house::RENT_TYPE]);
            echo "</div>";
            ?>
        </div>
    </section>
    <?php echo Html::submitButton(($model->isNewRecord) ?
        Yii::t('app', 'create_house') :
        Yii::t('app', 'save_changes'),
        ['class' => $model->isNewRecord ? 'btn btn-outline-success submit' :
            'btn btn-outline-primary submit', 'name' => 'submit']) ?>
    <?php ActiveForm::end(); ?>
</div>

