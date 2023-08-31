<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;

\backend\assets\AppAsset::register($this);
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
<body>
<?php $this->beginBody() ?>

<div class="wrapper login-wrapper">

    <div class="main login-main">
        <?php if(Yii::$app->session->hasFlash('success')) {
            echo '<div class="alert alert-success" role = "alert" >
                <span>' .Yii::$app->session->getFlash('success').'</span>
            </div >';
        }
        if(Yii::$app->session->hasFlash('error')) {
            echo '<div class="alert alert-danger" role = "alert" >
                <a href = "#" class="alert-link" >'
                .Yii::$app->session->getFlash('error').'</a>
            </div >';
        }
        ?>
        <?= \yii\bootstrap4\Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs'])
                ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <div class="main-content login-content">
            <h5 class="main-header"><?php echo $this->title ?></h5>
            <hr>
            <?= $content ?>
        </div>
    </div>
</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
