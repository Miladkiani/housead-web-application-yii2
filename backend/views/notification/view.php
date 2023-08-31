<?php

use backend\utils\Tools;
use \yii\helpers\Html;
use backend\models\Notification;
?>
<div class="main-view notification-view">
    <article <?php
   if ($model->status==Notification::STATUS_SENT) {
       echo "class='sent'";
    }else{
       echo "class='ready'";
   }
    ?> >
        <div class="col-item col-item-1">
            <?php
            $url = $model->getSmallIconPath();
            if (isset($url)) {
                echo Html::img($url,
                    [
                        'alt' => Yii::t('app', 'small_icon'),

                    ]
                );
            }
            ?>
            <span><?= Yii::t('app', 'house_ad_app_name') ?></span>
            <br>
            <span><?= Tools::changeToFa($model->title) ?></span>
            <a><?= Tools::changeToFa($model->small_body) ?></a>
            <a><?= Tools::changeToFa($model->big_body) ?></a>
        </div>
        <div class="col-item col-item-2">
            <?php
            $url = $model->getLargeIconPath();
            if (isset($url)) {
                echo Html::img($url,
                    [
                        'alt' => Yii::t('app', 'large_icon'),
                        'class' => ['avatar']
                    ]
                );
            }
            ?>
        </div>
    </article>
</div>