<?php

/* @var $this \yii\web\View */

/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;


AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body dir="rtl">
<?php
$this->beginBody();
?>
<div  class="wrapper">
    <?= $this->render('navigation')?>
    <?= $this->render('nav-top')?>
    <div id="main" class="main">
        <?= \yii\bootstrap4\Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs'])
                        ? $this->params['breadcrumbs'] : [],
            ]) ?>
        <div class="main-content">
            <?= $content ?>
        </div>
    </div>
</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
