<?php

use common\models\User;
use kartik\dialog\Dialog;
use yii\bootstrap4\Html;


?>
<div class="main-list admin-list">

    <?php
    $this->title = Yii::t('app', 'users');
    $this->params['breadcrumbs'][] = $this->title;
    echo Dialog::widget([
        'libName' => 'krajeeDialog',
    ]);
    echo \kartik\grid\GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel'=>$searchModel,
            'pjax'=>true,
            'options'=>[
                'id'=>'admin-grid'
            ],
            'pjaxSettings'=>[
                'options'=>[
                    'id'=>'admin-grid-pjax'
                ]
            ],
            'columns' => [
                ['class' => 'kartik\grid\SerialColumn',
                    'contentOptions' => ['class' => 'kartik-sheet-style'],
                    'width' => '36px',
                    'pageSummary' => 'Total',
                    'pageSummaryOptions' => ['colspan' => 6],
                    'header' => '',
                    'headerOptions' => ['class' => 'kartik-sheet-style']
                ],
                [
                    'attribute' => 'image',
                    'label' => Yii::t('app', 'avatar'),
                    'format' => 'html',
                    'vAlign' => 'middle',
                    'hAlign' => 'center',
                    'value' => function ($model) {

                        $path = "@web/images/avatar.png";
                        if (isset($model->avatar)) {
                            $path = '@web/uploads/user/' . $model->avatar->image_web_filename;
                        }
                        return \yii\helpers\Html::img($path, ['class'=>'avatar']);
                    }
                ],
                [
                    'attribute' => 'first_name',
                    'vAlign' => 'middle',
                    'hAlign' => 'center',
                ],
                [
                    'attribute' => 'last_name',
                    'vAlign' => 'middle',
                    'hAlign' => 'center',
                ],
                [
                    'attribute' => 'username',
                    'vAlign' => 'middle',
                    'hAlign' => 'center',
                ],
                [
                    'attribute' => 'email',
                    'vAlign' => 'middle',
                    'hAlign' => 'center',
                ],
                [
                    'attribute' => 'phone',
                    'vAlign' => 'middle',
                    'hAlign' => 'center',
                ],
                [
                    'class' => 'kartik\grid\EnumColumn',
                    'attribute' => 'role.item_name',
                    'label' => Yii::t('app','role'),
                    'vAlign' => 'middle',
                    'hAlign' => 'center',
                    'enum' => [
                        'employee' => Yii::t('app', 'employee'),
                        'admin' => Yii::t('app', 'admin'),
                        'chief' => Yii::t('app', 'chief')
                    ],
                    'filter' => [
                        'employee' => Yii::t('app', 'employee'),
                        'admin' => Yii::t('app', 'admin'),
                        'chief' => Yii::t('app', 'chief')
                    ],
                ],
                [
                    'class' => 'kartik\grid\EnumColumn',
                    'attribute' => 'status',
                    'vAlign' => 'middle',
                    'hAlign' => 'center',
                    'width'=>'120px',
                    'format'=>'html',
                    'enum' => [
                        User::STATUS_ACTIVE => '<p class="active">'.Yii::t('app', 'active').'</p>',
                        User::STATUS_INACTIVE => '<p class="inactive">'.Yii::t('app', 'inactive').'</p>',
                        User::STATUS_DELETED => '<p class="deleted">'.Yii::t('app', 'deleted').'</p>',
                    ],
                    'filter' => [
                        User::STATUS_ACTIVE => Yii::t('app', 'active'),
                        User::STATUS_INACTIVE => Yii::t('app', 'inactive'),
                        User::STATUS_DELETED => Yii::t('app', 'deleted'),
                    ],

                ],
                [
                        'attribute'=>'last_login_time',
                        'vAlign' => 'middle',
                        'hAlign' => 'center',
                        'content'=>function($model){
                             return Yii::$app->formatter->asDatetime($model->last_login_time);
                        }
                    ],
                ['class' => 'kartik\grid\ActionColumn',
                    'template'=>'{update}',
                    'buttons' => [
                        'update' => function ($url) {
                            $options = [
                                'title' => Yii::t('app','update'),
                                'aria-label' => Yii::t('app','update'),
                                'data-pjax' => '0',
                                'class' => 'btn-primary btn-circle',
                            ];
                            $icon = Html::tag('i', '', ['class' => "fa fa-pencil-alt"]);
                            return Html::a($icon,$url, $options);
                        },
                    ],
                    'headerOptions' => ['class' => 'kartik-sheet-style'],
                ]

            ],

            'containerOptions' => ['style' => 'overflow: auto'],
            'headerRowOptions' => ['class' => 'kartik-sheet-style'],
            'filterRowOptions' => ['class' => 'kartik-sheet-style'],
            // set your toolbar
            'toolbar' => [
                [
                    'content' =>
                        \yii\helpers\Html::a('<i class="fa fa-plus"></i>',
                            ['create'], [
                                'class' => 'btn btn-success',
                            ]) . ' ' .
                        \yii\helpers\Html::a('<i class="fa fa-redo"></i>',
                            ['index'], [
                                'class' => 'btn btn-secondary',
                                'title' => Yii::t('app', 'redo'),
                            ]),
                    'options' => ['class' => 'btn-group mr-2']
                ],
                '{toggleData}',
            ],
            'panel' => [
                'heading' => '<h5 class="panel-title"><i class="fa fa-user-tie"></i> '
                    . Yii::t('app', 'users') . '</h5>',
                'type' => 'secondary',
            ],
            'resizableColumns' => false,
            'hover' => true,
        ]
    );
    ?>

</div>
