<?php
use \backend\utils\Tools;
use \common\models\House;
use \kartik\grid\GridView;
use \yii\bootstrap4\Html;
use \kartik\dialog\Dialog;
?>
<div class="main-list house-list">
    <?php
    $this->title = Yii::t('app', 'houses');
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app','houses')];
    echo Dialog::widget([
        'libName' => 'krajeeDialog',
    ]);
    echo GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'pjax'=>true,
            'options'=>[
                    'id'=>'house-grid'
                ],
            'pjaxSettings'=>[
                    'options'=>[
                            'id'=>'house-grid-pjax'
                    ]
            ],
            'columns' => [
                [
                    'class' => 'kartik\grid\SerialColumn',
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
                    'headerOptions' => ['class' => 'kartik-sheet-style'] ,
                    'expandOneOnly' => true
                ],
                [
                    'attribute' => 'id',
                    'vAlign' => 'middle',
                    'hAlign' => 'center',
                    'value' => function ($model) {
                        return Tools::changeToFa($model->id);
                    }
                ],
                [
                    'attribute' => 'title',
                    'vAlign' => 'middle',
                    'value' => function ($model) {
                        return Tools::changeToFa($model->title,0);
                    }
                ],
                [
                    'attribute' => 'category_id',
                    'value' => function ($model, $key, $index, $widget) {
                        return $model->category->title;
                    },
                    'group' => true,
                    'groupedRow' => true,                    // move grouped column to a single grouped row
                    'groupOddCssClass' => 'kv-grouped-row',  // configure odd group cell css class
                    'groupEvenCssClass' => 'kv-grouped-row', // configure even group cell css class
                ],
                [
                    'attribute' => 'city.id',
                    'vAlign' => 'middle',
                    'label' => Yii::t('app', 'city'),
                    'value' => function ($model, $key, $index, $widget) {
                        return $model->city->name;
                    },
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => \yii\helpers\ArrayHelper::map(
                        \common\models\City::find()->orderBy('name')->asArray()->all(),
                        'id', 'name'),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' =>
                        Yii::t('app', 'all_city')],
                    'group' => true,  // enable grouping
                    'subGroupOf' => 1 // supplier column index is the parent group
                ],
                [
                    'attribute' => 'neighborhood_id',
                    'vAlign' => 'middle',
                    'value' => function ($model, $key, $index, $widget) {
                        return $model->neighborhood->name;
                    },
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => \yii\helpers\ArrayHelper::map(
                        \common\models\Neighborhood::find()->orderBy('name')
                            ->asArray()
                            ->all(),
                        'id', 'name'),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' =>
                        Yii::t('app', 'all_neighborhood')],
                    'group' => true,  // enable grouping
                    'subGroupOf' => 2 // supplier column index is the parent group
                ],

                [
                    'class' => 'kartik\grid\EnumColumn',
                    'attribute' => 'lease_type',
                    'vAlign' => 'middle',
                    'hAlign' => 'center',
                    'width' => '100px',
                    'enum' => [
                        House::RENT_TYPE => Yii::t('app', 'rent'),
                        House::SELL_TYPE => Yii::t('app', 'sell')
                    ],
                    'filter' => [
                        House::RENT_TYPE => Yii::t('app', 'rent'),
                        House::SELL_TYPE => Yii::t('app', 'sell')
                    ],

                ],
                [
                    'attribute' => 'sell',
                    'vAlign' => 'middle',
                    'hAlign' => 'center',
                    'value' => function ($model) {
                        return Tools::changeToFa($model->sell, true);
                    },
                    'filterOptions' => ['class' => 'price fa-number just-number ltr-input']
                ],
                [
                    'attribute' => 'prepayment',
                    'vAlign' => 'middle',
                    'hAlign' => 'center',
                    'value' => function ($model) {
                        return Tools::changeToFa($model->prepayment, true);
                    },
                    'filterOptions' => ['class' => 'price fa-number just-number ltr-input']
                ],
                [
                    'attribute' => 'rent',
                    'vAlign' => 'middle',
                    'hAlign' => 'center',
                    'value' => function ($model) {
                        return Tools::changeToFa($model->rent, true);
                    },
                    'filterOptions' => ['class' => 'price fa-number just-number ltr-input']
                ],
                [
                    'attribute' => 'author.username',
                    'vAlign' => 'middle',
                    'hAlign' => 'center',
                    'label'=>Yii::t('app','author_username')
                ],
                [
                    'class' => 'kartik\grid\EnumColumn',
                    'attribute' => 'status',
                    'vAlign' => 'middle',
                    'hAlign' => 'center',
                    'width' => '110px',
                    'format'=>'html',
                    'enum' => [
                        House::STATUS_ACTIVE => '<p class="active" style="margin-bottom: 0">'.Yii::t('app', 'active').'</span></p>',
                        House::STATUS_INACTIVE => '<p class="inactive" style="margin-bottom: 0">'.Yii::t('app', 'inactive').'</p>',
                    ],
                    'filter' => [
                        House::STATUS_ACTIVE => Yii::t('app', 'active'),
                        House::STATUS_INACTIVE => Yii::t('app', 'inactive')
                    ],
                ],
                ['class' => 'kartik\grid\ActionColumn',
                    'template'=>'{delete}{update}{status}',
                    'visibleButtons'=>[
                            'status'=>  function($model){
                                    return
                                        Yii::$app->user->can('activeHouse',
                                    ['post'=>$model]);
                            },
                            'delete'=> function($model){
                                    return
                                        Yii::$app->user->can('deleteHouse',
                                    ['post'=>$model]);
                            },
                            'update'=>function($model){
                                    return
                                        Yii::$app->user->can('updateHouse',
                                    ['post'=>$model]);
                            },
                    ],
                    'buttons' => [
                            'status'=>function($url,$model){
                                if ($model->status == House::STATUS_ACTIVE) {
                                    $title = Yii::t('app','inactive');
                                    $class = 'btn-warning btn-circle';
                                    $icon = 'lock';
                                }else{
                                    $title = Yii::t('app','active');
                                    $class = 'btn-success btn-circle';
                                    $icon = 'lock-open';
                                }

                                $options = [
                                    'title' => $title,
                                    'aria-label' => $title,
                                    'class' => $class ,
                                    'onclick' => "
                                     krajeeDialog.confirm('".Yii::t('app','ask_accept_change_status')."', function(out){
                                            if(out) {
                                                 $.ajax('$url', {
                                                    type: 'POST'
                                                    }).done(function(data) {
                                                $.pjax.reload({container: '#house-grid-pjax'});
                                                });
                                            }
                                        });
                                     ",
                                ];
                                $icon = Html::tag('i', '', ['class' => "fa fa-$icon"]);
                                return Html::a($icon, '#', $options);
                            },
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
                                                    type: 'POST'
                                                    }).done(function(data) {  
                                                $.pjax.reload({container: '#house-grid-pjax'});
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
                                'title' => Yii::t('app', 'refresh'),
                            ]),
                    'options' => ['class' => 'btn-group mr-2']
                ],
                '{export}',
                '{toggleData}',
            ],
            'toggleDataContainer' => ['class' => 'btn-group mr-2'],
            // set export properties
            'exportConfig' => [
                GridView::EXCEL => [
                    'label' => Yii::t('app', 'excel'),
                    'iconOptions' => ['class' => 'text-success'],
                    'showHeader' => true,
                    'showPageSummary' => true,
                    'showFooter' => true,
                    'showCaption' => true,
                    'filename' => Yii::t('app', 'grid_house_export'),
                    'alertMsg' =>
                        Yii::t('app', 'The EXCEL export file will be generated for download'),
                    'options' => ['title' => Yii::t('app', 'Microsoft Excel 95+')],
                    'mime' => 'application/vnd.ms-excel',
                    'config' => [
                        'worksheet' => Yii::t('app', 'house_worksheet'),
                        'cssFile' => ''
                    ]
                ]
            ],
            'panel' => [
                'heading' => '<h5 class="panel-title"><i class="fa fa-home"></i> '
                    . Yii::t('app', 'houses') . '</h5>',
                'type' => 'success',

            ],
            'resizableColumns' => false,
            'hover' => true,

        ]
    );
    ?>
</div>
