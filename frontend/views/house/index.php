<?php

use backend\utils\Tools;
use common\models\Category;
use common\models\City;
use common\models\Feature;
use common\models\House;
use kartik\form\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\ListView;
use yii\widgets\Pjax;

$this->title = Yii::t('app','house_ad');

$options = [
    'neighborhoodUrl' => Url::to(['/neighborhood/list']),
    'rangeUrl' => Url::to(['/house/range']),
];

$this->registerJs(
    "var yiiOptions = " . Json::htmlEncode($options) . ";",
    View::POS_HEAD,
    'yiiOptions'
);

?>

<div class="house-overview">
    <div class="nav-top">
        <div class="toolbar">
            <span class="nav-icon"><i class="fa fa-bars"></i></span>
            <span class="title light app-name">
                <i class="fa fa-home"></i>
                <?= Yii::t('app', 'houses') ?>
            </span>
        </div>
        <div class="search-bar">
            <?php
            $searchForm = ActiveForm::begin([
                'enableAjaxValidation' => false,
                'enableClientValidation' => false,
                'options' => ['data-pjax-form' => true],
            ]);
            echo $searchForm->field($searchModel, 'token', [
                'feedbackIcon' => ['default' => 'search']
            ])->textInput(['placeholder' => Yii::t('app', 'search_placeholder')])
                ->label(false);

            ActiveForm::end();
            ?>
        </div>
    </div>
    <div class="main-container">
        <div class="sidebar">
            <div class="sidebar-top">
                <span class="close-icon"><i class="fa fa-times"></i></span>
                <span class="subtitle1 light">
                    <?= Yii::t('app', 'filters') ?>
                </span>
            </div>
            <div class="container">
                <?php
                $form = ActiveForm::begin([
                    'id' => 'filter_form',
                    'enableAjaxValidation' => false,
                    'enableClientValidation' => false,
                    'options' => ['data-pjax-form' => true],
                ]); ?>
                <div class="filter-group filter-group-category">
                    <span class="subtitle1 filter-group-title">
                        <?php echo Yii::t('app', 'categories'); ?></span>
                    <div class="filter-group-item-list filter-group-item-list-category">
                        <?php
                        $categoriesList = ArrayHelper::map(Category::find()->all(), 'id', 'title');
                        echo $form->field($searchModel, 'category_id', ['options' => ['class' => 'body1']])
                            ->checkboxList($categoriesList)->label(false);
                        ?>
                    </div>
                </div>

                <div class="filter-group filter-group-neighborhood">
                    <span class="subtitle1 filter-group-title">
                        <?php echo Yii::t('app', 'neighborhoods') ?>
                    </span>
                    <div class="filter-group-item-list">
                        <?php
                        $cities =
                            ArrayHelper::map(City::find()->all(), 'id', 'name');
                        echo $form->field($searchModel, 'city')->widget(
                            Select2::className(), [
                            'data' => $cities,
                            'size' => Select2::SMALL,
                            'options' => [
                                'placeholder' => Yii::t(
                                    'app',
                                    'city_prompt'),
                                'class' => 'dropdown',
                                'dir' => 'rtl'
                            ],

                        ])->label(false);
                        ?>
                    </div>
                    <div class="filter-group-item-list filter-group-item-list-neighborhood">
                    </div>
                </div>

                <div class="filter-group filter-group-room">
                    <span class="subtitle1 filter-group-title">
                        <?php echo Yii::t('app', 'bedroom') ?></span>
                    <div class="filter-group-item-list filter-group-item-list-room">
                        <?php
                        $rooms = [
                            '1' => Tools::changeToFa(1), '2' => Tools::changeToFa(2),
                            '3' => Tools::changeToFa(3), '4' => Tools::changeToFa(4),
                            '5' => Tools::changeToFa(5), '6' => Tools::changeToFa(6)
                        ];
                        echo $form->field($searchModel, 'room', ['options' => ['class' => 'body1']])
                            ->checkboxList($rooms)->label(false);
                        ?>
                    </div>
                </div>
                <div class="filter-group filter-group-feature">
                    <span class="subtitle1 filter-group-title">
                        <?php echo Yii::t('app', 'features'); ?></span>
                    <div class="filter-group-item-list filter-group-item-list-feature">
                        <?php
                        $featureList = ArrayHelper::map(Feature::find()->all(), 'id', 'title');
                        echo $form->field($searchModel, 'feature_id',
                            ['options' => ['class' => 'body1']])
                            ->checkboxList($featureList)->label(false);
                        ?>
                    </div>
                </div>

                <div class="filter-group filter-group-price">
                    <span class="subtitle1 filter-group-title">
                        <?php echo Yii::t('app', 'price'); ?>
                    </span>
                    <div class="filter-group-item-list filter-group-item-list-price">

                        <?php
                        echo $form->field($searchModel, 'lease_type')->radioList(
                            array(house::SELL_TYPE => Yii::t('app', 'sell'),
                                house::RENT_TYPE => Yii::t('app', 'rent')),
                            [
                                'item' => function ($index, $label, $name, $checked, $value) {
                                    $checked = ($checked) ? 'checked' : '';
                                    $return = '<div class="radio">';
                                    $return .= '<label class="body1">';
                                    $return .= '<input type="radio"  name="' . $name . '" value="' . $value .
                                        '" ' . $checked . ' >';
                                    $return .= '<span>' . ' ' . ucwords($label) . '</span>';
                                    $return .= '</label>';
                                    $return .= '</div>';
                                    return $return;
                                },
                            ]
                        )->label(false);
                        ?>

                        <div class="house-lease rent-lease">

                            <div>
                                <?php
                                echo $form->field($searchModel, 'min_prepayment',
                                    ['options' => ['id' => 'hidden_minimum_prepayment', 'class' => 'house_price']])
                                    ->hiddenInput()->label(false);
                                echo $form->field($searchModel, 'max_prepayment',
                                    ['options' => ['id' => 'hidden_maximum_prepayment', 'class' => 'house_price']])
                                    ->hiddenInput()->label(false);
                                ?>
                                <span class="range-title range-title-prepayment body2">
                                <?php echo Yii::t('app', 'prepayment') ?>
                            </span>
                                <div>
                                    <span class="range-label-min range-label-min-prepayment caption fa-number"></span>
                                    <span class="range-label-max range-label-max-prepayment caption fa-number"></span>
                                </div>
                                <div class="range-slider range-slider-prepayment"></div>
                            </div>

                            <div>
                                <?php
                                echo $form->field($searchModel, 'min_rent',
                                    ['options' => ['id' => 'hidden_minimum_rent', 'class' => 'house_price']])
                                    ->hiddenInput()->label(false);
                                echo $form->field($searchModel, 'max_rent',
                                    ['options' => ['id' => 'hidden_maximum_rent', 'class' => 'house_price']])
                                    ->hiddenInput()->label(false);
                                ?>
                                <span class="range-title range-title-rent body2">
                                <?php echo Yii::t('app', 'rent') ?>
                            </span>
                                <div>
                                    <span class="range-label-min range-label-min-rent caption fa-number"></span>
                                    <span class="range-label-max range-label-max-rent caption fa-number"></span>
                                </div>
                                <div class="range-slider range-slider-rent"></div>
                            </div>

                        </div>

                        <div class="house-lease sell-lease">
                            <?php
                            echo $form->field($searchModel, 'min_sell',
                                ['options' => ['id' => 'hidden_minimum_sell', 'class' => 'house_price']])
                                ->hiddenInput()->label(false);
                            echo $form->field($searchModel, 'max_sell',
                                ['options' => ['id' => 'hidden_maximum_sell', 'class' => 'house_price']])
                                ->hiddenInput()->label(false);
                            ?>
                            <span class="range-title range-title-prepayment body2">
                            <?php echo Yii::t('app', 'sell') ?>
                        </span>
                            <div>
                                <span class="range-label-min range-label-min-sell caption fa-number"></span>
                                <span class="range-label-max range-label-max-sell caption fa-number"></span>
                            </div>
                            <div class="range-slider range-slider-sell"></div>
                        </div>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
        <?php
        Pjax::begin([
            'id' => 'pjax_list_house',
            'options' => ['class' => 'house-list-container']
        ]);
        ?>
        <?php
        echo ListView::widget([
            'options' => [
                'tag' => 'div',
                'class' => 'house-list'
            ],
            'dataProvider' => $dataProvider,
            'itemView' => function ($model, $key, $index, $widget) {
                $itemContent = $this->render('_list_item_house', ['model' => $model]);
                return $itemContent;
            },
            'itemOptions' => [
                'tag' => 'div',
                'class' => 'house-item'
            ],

            /* do not display {summary} */
            'summary' => '',

            'emptyText' => '<div class="alert alert-info " role="alert">' .
                Yii::t('app','no_result_found')
                . '</div>',


            'layout' => '{items}{pager}',

            'pager' => [
                'prevPageLabel' => '&laquo;',

                'nextPageLabel' => '&raquo;',

                'maxButtonCount' => 5,
                'options' => [
                    'class' => 'pagination  justify-content-center'
                ],
                'linkContainerOptions' => ['class' => 'page-item fa-number'],
                'disabledListItemSubTagOptions' => ['class' => 'page-link'],
                'linkOptions' => ['class' => 'page-link'],
            ],

        ]);
        ?>
        <?php Pjax::end() ?>
    </div>
</div>
