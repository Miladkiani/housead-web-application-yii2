<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
        '@uploadPath'=>'/web/uploads/',
        '@houseUploadPath'=>'/web/uploads/house',
        '@houseThumbUploadPath'=>'/web/uploads/house/thumb',
        '@userUploadPath'=>'/web/uploads/user',
        '@userUploadUrl'=>'/uploads/user',
        '@houseUploadUrl'=>'/backend/web/uploads/house',
        '@houseThumbUploadUrl'=>'/backend/web/uploads/house/thumb',
        '@smallIconUploadUrl'=>'/uploads/notification/smallIcon',
        '@largeIconUploadUrl'=>'/uploads/notification/largeIcon',
        '@smallIconUploadPath'=>'/web/uploads/notification/smallIcon',
        '@largeIconUploadPath'=>'/web/uploads/notification/largeIcon'
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'sourceLanguage' => 'en-US',
    'language'=>'fa-IR',
    'components' => [
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'locale' => 'fa_IR@calendar=persian',
            'calendar' => \IntlDateFormatter::TRADITIONAL,
            'dateFormat' => 'php:Y-m-d',
            'datetimeFormat' => 'php:Y-m-d H:i',
            'timeFormat' => 'php:H:i',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'authManager'=>[
            'class'=>'yii\rbac\DbManager'
        ]
    ],
];
