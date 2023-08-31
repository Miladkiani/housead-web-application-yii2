<?php

use common\models\House;
use kartik\form\ActiveForm;
use yii\helpers\Html;
use kartik\file\FileInput;
use backend\models\Notification;

?>
<div class="main-form notification-form">
    <?php
    $this->title =
        ($model->isNewRecord) ? Yii::t('app', 'create_new_notification')
            : Yii::t('app', 'update_notification');
    $this->params['breadcrumbs'][] = [
        'label' => Yii::t('app', 'notifications'),
        'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;
    $form = ActiveForm::begin();
    ?>
    <section>
        <div class="form-group form-group-1">
            <?php
            echo $form->field($model, 'title')->textInput(
                ['autofocus' => true,
                    'placeholder' => Yii::t('app', 'notification_title_placeholder'),
                    'class' => 'fa-number']);
            echo $form->field($model, 'small_body')->textInput(
                [
                    'placeholder' =>
                        Yii::t('app', 'notification_small_body_placeholder'),
                    'class' => 'fa-number']);
            echo $form->field($model, 'big_body')->textarea(
                [
                    'placeholder' =>
                        Yii::t('app', 'notification_big_body_placeholder'),
                    'class' => 'fa-number']);

            echo $form->field($model, 'time_to_live')->radioList(
                array(Notification::ONE_WEEK => Yii::t('app', 'one_week'),
                    Notification::TWO_WEEK => Yii::t('app', 'two_week'),
                    Notification::THREE_WEEK => Yii::t('app', 'three_week'),
                    Notification::FOUR_WEEK => Yii::t('app', 'four_week'),),
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
            ?>

        </div>
    </section>
    <section>
        <div class="form-group form-group-2">
            <div class="col-input col-input-small-icon">
                <h6><?= Yii::t('app', 'small_icon') ?></h6>
                <div class="box">
                    <?php

                    $path = '@web/images/photo.png';
                    $class = 'notification-icon notification-small-icon';
                    $preview = $model->getSmallIconPath();
                    if (isset($preview)) {
                        $path = $preview;
                        $class = $class . ' ' . 'avatar';
                    }
                    echo Html::img($path,
                        [
                            'alt' => Yii::t('app', 'small_icon'),
                            'class' => [$class],
                        ]
                    );
                    ?>
                </div>

                <?php echo $form->field($model, 'small_icon')
                    ->widget(FileInput::classname(), [
                        'options' => ['multiple' => false, 'accept' => 'image/*'],
                        'pluginOptions' =>
                            ['allowedFileExtensions' => ['jpg', 'jpeg', 'png'],
                                'showPreview' => false,
                                'showRemove' => false,
                                'showUpload' => false,
                                'showCancel' => false,
                                'showCaption' => false,
                                'maxFileCount' => 1,
                                'minImageWidth' => 24,
                                'minImageHeight' => 24,
                                'maxImageWidth' => 128,
                                'maxImageHeight' => 128,
                                'browseClass' => 'btn btn-success btn-upload',
                                'browseIcon' => '<i class="fa fa-camera"></i> ',
                                'browseLabel' => Yii::t('app', 'select_photo')
                            ],
                    ])->label('');
                ?>
            </div>
            <div class="col-input col-input-large-icon">
                <h6><?= Yii::t('app', 'large_icon') ?></h6>
                <div class="box">
                    <?php

                    $path = '@web/images/photo.png';
                    $class = 'notification-icon notification-large-icon';
                    $preview = $model->getLargeIconPath();
                    if (isset($preview)) {
                        $path = $preview;
                        $class = $class . ' ' . 'avatar';
                    }
                    echo Html::img($path,
                        [
                            'alt' => Yii::t('app', 'large_icon'),
                            'class' => [$class],
                        ]
                    );
                    ?>
                </div>

                <?php echo $form->field($model, 'large_icon')
                    ->widget(FileInput::classname(), [
                        'options' => ['multiple' => false, 'accept' => 'image/*'],
                        'pluginOptions' =>
                            ['allowedFileExtensions' => ['jpg', 'jpeg', 'png'],
                                'showPreview' => false,
                                'showRemove' => false,
                                'showUpload' => false,
                                'showCancel' => false,
                                'showCaption' => false,
                                'maxFileCount' => 1,
                                'minImageWidth' => 128,
                                'minImageHeight' => 128,
                                'maxImageWidth' => 256,
                                'maxImageHeight' => 256,
                                'browseClass' => 'btn btn-success btn-upload',
                                'browseIcon' => '<i class="fa fa-camera"></i> ',
                                'browseLabel' => Yii::t('app', 'select_photo')
                            ],
                    ])->label('');
                ?>
            </div>
        </div>
    </section>
    <br>
    <?php
    echo Html::submitButton(
        ($model->isNewRecord) ? Yii::t('app', 'create_notification')
            : Yii::t('app', 'save_changes'),
        ['class' => ($model->isNewRecord) ?
            'btn btn-outline-success submit' : 'btn btn-outline-primary submit', 'name' => 'submit'])
    ?>
    <?php ActiveForm::end(); ?>
</div>