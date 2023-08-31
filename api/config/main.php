<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-api',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'api\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        'v1' => [
            'basePath' => '@app/modules/v1',
            'class' => 'api\modules\v1\Module' // here is our v1 modules
        ]
    ],
    'components' => [
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => false,
            'enableSession'=>false,
            'loginUrl'=>null,
        ],
        'request' => [
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                [
                    'class'=>'yii\rest\UrlRule',
                    'controller'=>'v1/house',
                    'extraPatterns'=>[
                        'POST search' => 'search',
                        'GET range' => 'range',
                    ],
                    'except'=>['delete','update','create'],
                ],
                [
                    'class'=>'yii\rest\UrlRule',
                    'controller'=>'v1/feature',
                    'except'=>['delete','update','create','view'],
                ],
                [
                    'class'=>'yii\rest\UrlRule',
                    'controller'=>'v1/category',
                    'except'=>['delete','update','create','view'],
                ],
                [
                    'class'=>'yii\rest\UrlRule',
                    'controller'=>'v1/city',
                    'except'=>['delete','update','create','view'],
                ],
                [
                    'class'=>'yii\rest\UrlRule',
                    'controller'=>'v1/neighborhood',
                    'except'=>['delete','update','create','view'],
                ]
            ],
        ],

    ],
    'params' => $params,
];
