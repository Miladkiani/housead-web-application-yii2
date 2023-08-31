<?php

use yii\helpers\Url;

/* @var $this yii\web\View */

$this->title = Yii::t('app','house_ad_app');
?>
<div class="intro">
    <div class="intro-container">
        <div class="header">
            <span class="title light">
                <?= Yii::t('app', 'house_ad_app') ?>
            </span>
        </div>
        <div class="main-container">
            <div class="description">
                <p>
                    <?= Yii::t('app', 'app_description_1') ?>
                </p>
                <p>
                    <?= Yii::t('app', 'app_description_2') ?>
                </p>
                <p>
                    <?= Yii::t('app', 'app_description_3') ?>
                </p>
            </div>
            <div class="version">
                <div class="android-version">
                    <a href="#">
                        <?= Yii::t('app', 'android_version') ?>
                        <img src="<?php echo Url::to('@web/images/android-logo.svg') ?>" width="24" height="24">
                    </a>
                </div>
                <div class="web-version">
                    <a href="<?php echo Url::to(['/house'])?>">
                        <?= Yii::t('app', 'web_version') ?>
                        <img src="<?php echo Url::to('@web/images/web-logo.svg') ?>" width="16" height="16">
                    </a>
                </div>
            </div>
        </div>
        <div class="footer">
            <span class="subtitle1">
                <?= Yii::t('app', 'design_develop_by') ?>
            </span>
            <span class="subtitle1">
                <?= Yii::t('app', 'milad_kiani') ?>
            </span>
        </div>
    </div>
</div>
