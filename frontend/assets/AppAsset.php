<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css?v=1',
        'css/bootstrap-rtl.min.css',
        'css/jquery-ui.min.css'
    ];
    public $js = [
        'js/jquery-ui.min.js',
        'js/filter.js',
        'js/slider.js',
        'js/sidebar.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap4\BootstrapAsset',
        'rmrevin\yii\fontawesome\CdnFreeAssetBundle'
    ];
}
