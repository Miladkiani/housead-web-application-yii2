<?php

use backend\utils\Tools;
use \yii\helpers\Html;
use yii\helpers\Url;

?>

<div class="main-view house-view">
    <div class="house-section house-title">
        <h3><?= Yii::t('app', 'house_detail') ?>
            <span><?= Tools::changeToFa(Html::encode($model->title)) . '  ' ?></span></h3>
        <h5><?= Yii::t('app', 'created_at') ?>
            <span>
                <?=
                Yii::$app->formatter->asDatetime(Html::encode($model->created_at))
                ?>
            </span>
        </h5>
        <h5><?= Yii::t('app', 'updated_at') ?>
            <span><?= Yii::$app->formatter->asDatetime(Html::encode($model->updated_at)) ?></span></h5>
    </div>
    <div class="house-section house-properties">
        <?php if (isset($model->room) && $model->room != 0) {
            echo '<p>' . Tools::changeToFa(Html::encode($model->room)) . ' '
                . Yii::t('app', 'bedroom') . '</p>';
        }
        ?>
        <p><?= Tools::changeToFa(Html::encode($model->size), true) . ' '
            . Yii::t('app', 'meter') ?></p>
        <?php if (isset($model->furniture) && $model->furniture != 0) {
            echo '<p>' . Yii::t('app', 'furniture') . '</p>';
        }
        ?>
    </div>
    <?php
    $gallery = $model->getGallerySrc();
    if (isset($gallery) && !empty($gallery)) {
        echo '<div class="house-gallery">';
        foreach ($gallery as $image) {
            echo "<div class='polaroid'>";
            echo "<img src='".$image['thumbnail']."' height='auto' alt='" . Html::encode($model->title) . "'>";
            echo '<h6>' . $image['filename'] . '</h6>';
            echo '</div>';
        }
        echo '</div>';
    }
    ?>
    <div class="house-section house-description">
        <h5><?= Yii::t('app', 'description') ?></h5>
        <p><?= Tools::changeToFa(Html::encode($model->description)) ?></p>
    </div>
    <hr>
    <div class="house-section house-location-section">
        <div class="house-location house-address">
            <h5><?= Yii::t('app', 'address') ?></h5>
            <p><?= Tools::changeToFa(Html::encode($model->address)) ?></p>
        </div>
        <div class="house-location house-postcode">
            <h5><?= Yii::t('app', 'postcode') ?></h5>
            <p><?= Tools::changeToFa(Html::encode($model->postcode)) ?></p>
        </div>
        <div class="house-location house-phone">
            <h5><?= Yii::t('app', 'phone') ?></h5>
            <p><?= Tools::changeToFa(Html::encode($model->phone)) ?></p>
        </div>
    </div>
    <hr>
    <div class="house-section house-feature">
        <h5><?= Yii::t('app', 'features') ?></h5>
        <?php if (isset($model->features) && !empty($model->features)) {
            foreach ($model->features as $feature) {
                echo '<span>' . Tools::changeToFa(Html::encode($feature->title)) . '</span>';
            }
        }
        ?>
    </div>
</div>