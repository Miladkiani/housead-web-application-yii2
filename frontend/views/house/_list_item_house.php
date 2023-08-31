<?php

use yii\helpers\Html;
use backend\utils\Tools;

?>
<a class="cursor" href="<?= \yii\helpers\Url::to(['/house/view','id'=>$model->id]) ?>">
    <div class="row1" style="background-image: url('<?= $model->getMainImage(); ?>');
            ">
        <span class='subtitle1 light'><?= Tools::changeToFa($model->title) ?></span>
    </div>
    <div class="row2">
        <div class="price">
            <?php if ( isset($model->lease_type) &&
                $model->lease_type == $model::SELL_TYPE)
                echo "<span class='body1'>" . Yii::t('app', 'sell') . ': ' .
                    Tools::changeToFa($model->sell, true) . ' ' .
                    Yii::t('app', 'tooman') . "</span>";
            else {
                echo "<span class='body1'>" . Yii::t('app', 'prepayment') . ': ' .
                    Tools::changeToFa($model->prepayment, true) . ' ' .
                    Yii::t('app', 'tooman') .
                    "</span>";
                echo "<span class='body1'>" . Yii::t('app', 'rent') . ': ' .
                    Tools::changeToFa($model->rent, true) . ' ' .
                    Yii::t('app', 'tooman') .
                    "</span>";
            }
            ?>
        </div>
        <div class="location">
            <?php echo "<span class='body1'>" . $model->getCityName() . '، ' . $model->getNeighborhoodName() . "</span>"; ?>
        </div>
    </div>
    <div class="row3 h-list">
        <?php
            if(isset($model->size) && $model->size!=0) {
                echo '<span class="body1"><i class="fa fa-home"></i>' . ' '
                    . Tools::changeToFa($model->size, true) .
                    Yii::t('app', 'meter') . '</span>';
            }
            if(isset($model->room) && $model->room!=0) {
                echo '<span class="body1"><i class="fa fa-bed"></i>' . ' '
                    . Tools::changeToFa($model->room, true) .
                    Yii::t('app', 'bedrooms') . '</span>';
            }
            if(isset($model->furniture)){
                echo '<span class="body1"><i class="fa fa-chair"></i>' . ' '
                    . Yii::t('app','furniture');
            }
        ?>
    </div>
</a>