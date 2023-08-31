<?php

use \backend\utils\Tools;
use common\models\House;
use kartik\dialog\Dialog;
use  \kartik\grid\GridView;
use yii\bootstrap4\Html;
use backend\models\Notification;

?>
<div class="main-list notification-list">

    <?php
    $this->title = Yii::t('app', 'notifications');
    $this->params['breadcrumbs'][] = $this->title;
    echo Dialog::widget([
        'libName' => 'krajeeDialog',
    ]);
    echo GridView::widget([
            'dataProvider' => $dataProvider,
            'pjax' => true,
            'options' => [
                'id' => 'notification-grid'
            ],
            'pjaxSettings' => [
                'options' => [
                    'id' => 'notification-grid-pjax'
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
                    'class' => 'kartik\grid\ExpandRowColumn',
                    'width' => '50px',
                    'value' => function ($model, $key, $index, $column) {
                        return GridView::ROW_COLLAPSED;
                    },

                    'detail' => function ($model, $key, $index, $column) {
                        return Yii::$app->controller->renderPartial('view', ['model' => $model]);
                    },
                    'headerOptions' => ['class' => 'kartik-sheet-style'],
                    'expandOneOnly' => true
                ],
                [
                    'attribute' => 'id',
                    'hAlign' => 'center',
                    'vAlign' => 'middle',
                    'value' => function ($model) {
                        return Tools::changeToFa($model->id);
                    }
                ],
                [
                    'attribute' => 'title',
                    'hAlign' => 'center',
                    'vAlign' => 'middle',
                    'value' => function ($model) {
                        return Tools::changeToFa($model->title);
                    }
                ],
                [
                    'attribute' => 'small_body',
                    'hAlign' => 'center',
                    'vAlign' => 'middle',
                    'value' => function ($model) {
                        return Tools::changeToFa(mb_substr($model->small_body, 0, 50));
                    }
                ],
                [
                    'attribute' => 'time_to_live',
                    'hAlign' => 'center',
                    'vAlign' => 'middle',
                    'value' => function ($model) {
                        switch ($model->time_to_live){
                            case Notification::FOUR_WEEK:
                                return Yii::t('app','four_week');
                            case Notification::THREE_WEEK:
                                return Yii::t('app','three_week');
                            case Notification::TWO_WEEK:
                                return Yii::t('app','two_week');
                            default:
                                return Yii::t('app','one_week');
                        }
                    }
                ],
                [
                    'attribute' => 'created_at',
                    'vAlign' => 'middle',
                    'hAlign' => 'center',
                    'value' => function ($model) {
                        return Yii::$app->formatter->asDatetime($model->created_at);
                    }
                ],
                [
                    'attribute' => 'updated_at',
                    'vAlign' => 'middle',
                    'hAlign' => 'center',
                    'value' => function ($model) {
                        return Yii::$app->formatter->asDatetime($model->updated_at);
                    }
                ],
                [
                    'attribute' => 'author.username',
                    'vAlign' => 'middle',
                    'hAlign' => 'center',
                    'label' => Yii::t('app', 'author_username'),
                ],
                [
                    'class' => 'kartik\grid\EnumColumn',
                    'attribute' => 'status',
                    'vAlign' => 'middle',
                    'hAlign' => 'center',
                    'width' => '120px',
                    'format' => 'html',
                    'enum' => [
                        Notification::STATUS_SENT => '<p class="active" style="margin-bottom: 0">' . Yii::t('app', 'sent') . '</span></p>',
                        Notification::STATUS_READY => '<p class="inactive" style="margin-bottom: 0">' . Yii::t('app', 'ready_for_sent') . '</p>',
                    ],
                    'filter' => [
                        Notification::STATUS_SENT => Yii::t('app', 'sent'),
                        Notification::STATUS_READY => Yii::t('app', 'ready_for_send')
                    ],
                ],
                [
                    'class' => 'kartik\grid\ActionColumn',
                    'template' => '{delete}{update}{status}',
                    'width' => '140px',
                    'visibleButtons' => [
                        'status' => function ($model) {
                            return
                                (Yii::$app->user->can('sendNotification',
                                        ['post' => $model]) && ($model->status == Notification::STATUS_READY));
                        },
                        'delete' => function ($model) {
                            return
                                Yii::$app->user->can('deleteNotification',
                                    ['post' => $model]);
                        },
                        'update' => function ($model) {
                            return
                                Yii::$app->user->can('updateNotification',
                                    ['post' => $model]);
                        }
                    ],
                    'buttons' => [
                        'status' => function ($url, $model) {
                            if ($model->status == Notification::STATUS_READY) {
                                $title = Yii::t('app', 'send');
                                $class = 'btn-success btn-circle';
                                $icon = 'arrow-right';
                                $options = [
                                    'title' => $title,
                                    'aria-label' => $title,
                                    'class' => $class,
                                    'onclick' => "
                                     krajeeDialog.confirm('" . Yii::t('app', 'ask_accept_send_notification') . "', function(out){
                                            if(out) {
                                                 $.ajax('$url', {
                                                    type: 'POST',
                                                    dataType: 'json'
                                                    }).done(function(data) {
                                                    if (data.status){
                                                     krajeeDialog.alert('" . Yii::t('app', 'notification_successfully_sent') . "');
                                                     $.pjax.reload({container: '#notification-grid-pjax'});
                                                    }else{
                                                      krajeeDialog.alert('" . Yii::t('app', 'notification_not_sent') . "');
                                                    }
                                                });
                                            }
                                        });
                                     ",
                                ];
                                $icon = Html::tag('i', '', ['class' => "fa fa-$icon"]);
                                return Html::a($icon, '#', $options);
                            } else {
                                return '';
                            }
                        },
                        'update' => function ($url, $model) {
                            $options = [
                                'title' => Yii::t('app', 'update'),
                                'aria-label' => Yii::t('app', 'update'),
                                'data-pjax' => '0',
                                'class' => 'btn-primary btn-circle',
                            ];
                            $icon = Html::tag('i', '', ['class' => "fa fa-pencil-alt"]);
                            return Html::a($icon, $url, $options);
                        },
                        'delete' => function ($url, $model) {
                            $options = [
                                'title' => Yii::t('app', 'delete'),
                                'aria-label' => Yii::t('app', 'delete'),
                                'class' => 'btn-danger btn-circle',
                                'onclick' => "
                                     krajeeDialog.confirm('" . Yii::t('app', 'ask_accept_delete') . "', function(out){
                                            if(out) {
                                                 $.ajax('$url', {
                                                    type: 'POST'
                                                    }).done(function(data) {
                                                $.pjax.reload({container: '#notification-grid-pjax'});
                                                });
                                            }
                                        });
                                     ",
                            ];
                            $icon = Html::tag('i', '', ['class' => "fa fa-trash"]);
                            return Html::a($icon, '#', $options);
                        }
                    ],
                    'headerOptions' => ['class' => 'kartik-sheet-style'],

                ]
            ], 'containerOptions' => ['style' => 'overflow: auto'],
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
                                'title' => Yii::t('app', 'refresh'),
                            ]),
                    'options' => ['class' => 'btn-group mr-2']
                ],
                '{toggleData}',
            ],
            'panel' => [
                'heading' => '<h5 class="panel-title"><i class="fa fa-bell"></i> '
                    . Yii::t('app', 'notifications') . '</h5>',
                'type' => 'success',
            ],
            'resizableColumns' => false,
            'hover' => true,
        ]
    );
    ?>
</div>
