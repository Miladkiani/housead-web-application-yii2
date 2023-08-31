<?php

use \backend\utils\Tools;
use kartik\dialog\Dialog;
use  \kartik\grid\GridView;
use yii\bootstrap4\Html;

?>
<div class="main-list category-list">

    <?php
    $this->title = Yii::t('app', 'categories');
    $this->params['breadcrumbs'][] = $this->title;
    echo Dialog::widget([
        'libName' => 'krajeeDialog',
    ]);
    echo GridView::widget([
            'dataProvider' => $dataProvider,
            'pjax'=>true,
            'options'=>[
                'id'=>'category-grid'
            ],
            'pjaxSettings'=>[
                'options'=>[
                    'id'=>'category-grid-pjax'
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
                    'attribute' => 'id',
                    'hAlign'=>'center',
                    'vAlign' => 'middle',
                    'value' => function ($model) {
                        return Tools::changeToFa($model->id);
                    }
                ],
                [
                    'attribute' => 'title',
                    'hAlign'=>'center',
                    'vAlign' => 'middle',
                    'value' => function ($model) {
                        return Tools::changeToFa(mb_substr($model->title, 0, 50));
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
                    'class' => 'kartik\grid\ActionColumn',
                    'template'=>'{delete}{update}',
                    'width'=>'140px',
                    'visibleButtons'=>[
                        'delete'=> function($model){
                            return
                                Yii::$app->user->can('deleteCategory',
                                    ['post'=>$model]);
                        },
                        'update'=>function($model){
                            return
                                Yii::$app->user->can('updateCategory',
                                    ['post'=>$model]);
                        }
                    ],
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
                        'delete' => function ($url) {
                            $options = [
                                'title' => Yii::t('app','delete'),
                                'aria-label' => Yii::t('app','delete'),
                                'class' => 'btn-danger btn-circle',
                                'onclick' => "
                                     krajeeDialog.confirm('".Yii::t('app','ask_accept_delete')."', function(out){
                                            if(out) {
                                                 $.ajax('$url', {
                                                    type: 'POST',
                                                     dataType: 'json'
                                                    }).done(function(data) {
                                                    if (data.count!=0)
                                                    {
                                                        krajeeDialog.alert('" . Yii::t('app', 'pay_attention') . " '+data.count+' " . Yii::t('app', 'category_delete_restrict') . "');
                                                    }else{
                                                        $.pjax.reload({container:'#category-grid-pjax'});
                                                    }
                                                });
                                            }
                                        });
                                     ",
                            ];
                            $icon = Html::tag('i', '', ['class' => "fa fa-trash"]);
                            return Html::a($icon,'#',$options);
                        },
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
                'heading' => '<h5 class="panel-title"><i class="fa fa-star"></i> '
                    . Yii::t('app', 'features') . '</h5>',
                'type' => 'success',
            ],
            'resizableColumns' => false,
            'hover' => true,
        ]
    );
    ?>
</div>
