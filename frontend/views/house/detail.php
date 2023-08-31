<?php
use \backend\utils\Tools;
use yii\helpers\Html;
?>
<?php $this->title = Yii::t('app','house_detail'); ?>
<div class="house-detail" dir="ltr">
    <?php $gallerySrc = $model->getGallerySrc(); ?>
    <div class="top-nav">
        <?php echo Html::a('<i class="fa fa-chevron-left"></i>' .
            Yii::t('app', 'back'),
            Yii::$app->request->referrer,
            ['class' => 'title']); ?>
    </div>
    <div class="content">
        <div class="gallery-container">
            <div class="preview-container">
                <?php
                if (isset($gallerySrc) && !empty($gallerySrc)) {
                    $i = 1;
                    $galleryNumber = count($gallerySrc);
                    foreach ($gallerySrc as $gallery) { ?>
                        <div class="slide">
                            <div class="numbertext subtitle1 light"><?php echo Tools::changeToFa($i++) . '/' . Tools::changeToFa($galleryNumber); ?></div>
                            <img src="<?= $gallery['original'] ?>" style="width:100%">
                        </div>
                        <?php
                    }
                    if ($galleryNumber > 1) {
                        echo "<a class='slide-next'>&#10095;</a>";
                        echo "<a class='slide-prev'>&#10094;</a>";
                    }
                    ?>
                    <?php
                }
                ?>
            </div>
            <!-- Next and previous buttons -->
            <!-- Thumbnail images -->
            <div class="gallery-row">
                <?php
                if (isset($gallerySrc) && !empty($gallerySrc)) {
                    $i = 1;
                    $galleryNumber = count($gallerySrc);
                    foreach ($gallerySrc as $gallery) { ?>
                        <div class="gallery-column">
                            <img class="demo cursor" src="<?= $gallery['thumbnail'] ?>" style="width:100%"
                                 data-val="<?= $i++ ?>">
                        </div>
                        <?php
                    }
                }
                ?>
            </div>
        </div>
        <div class="detail-container" dir="rtl">
            <section>
                <div class="detail-row"><span class="subtitle1"><?= Tools::changeToFa($model->title) ?></span></div>
                <div class="detail-row specs h-list">
                    <?php
                    if (isset($model->size) && $model->size != 0) {
                        echo '<span class="body1"><i class="fa fa-home"></i>' . ' '
                            . Tools::changeToFa($model->size, true) .
                            Yii::t('app', 'meter') . '</span>';
                    }
                    if (isset($model->room) && $model->room != 0) {
                        echo '<span class="body1"><i class="fa fa-bed"></i>' . ' '
                            . Tools::changeToFa($model->room, true) .
                            Yii::t('app', 'bedrooms') . '</span>';
                    }
                    if (isset($model->furniture)) {
                        echo '<span class="body1"><i class="fa fa-chair"></i>' . ' '
                            . Yii::t('app', 'furniture');
                    }
                    ?>
                </div>
                <div class="detail-row location">
                    <?php echo "<span class='body1'>" . $model->getCityName() . '، ' . $model->getNeighborhoodName() . "</span>"; ?>
                </div>
            </section>
            <section>
                <div class="detail-row last-update">
                    <?php echo "<span class='subtitle1'>" . Yii::t('app', 'last_update') . "</span>"; ?>
                    <?php echo "<span class='body1'>" . Yii::$app->formatter->asDatetime($model->updated_at) . "</span>"; ?>
                </div>
                <div class="detail-row contact-info">
                    <?php echo "<div class='label plus'><span class='subtitle1'>"
                        . Yii::t('app', 'contact_info') . "</span></div>"; ?>
                    <?php echo "<div class='info'><span class='body1'><i class='fa fa-phone'></i> "
                        . $model->getAuthorPhone() . "</span></div>"; ?>
            </section>
            <section>
                <div class="detail-row price">
                    <?php if (isset($model->lease_type) &&
                        $model->lease_type == $model::SELL_TYPE)
                        echo "<span class='subtitle1'>" . Yii::t('app', 'sell') . ': ' .
                            Tools::changeToFa($model->sell, true) . ' ' .
                            Yii::t('app', 'tooman') . "</span>";
                    else {
                        echo "<span class='subtitle1'>" . Yii::t('app', 'prepayment') . ': ' .
                            Tools::changeToFa($model->prepayment, true) . ' ' .
                            Yii::t('app', 'tooman') .
                            "</span>";
                        echo "<span class='subtitle1'>" . Yii::t('app', 'rent') . ': ' .
                            Tools::changeToFa($model->rent, true) . ' ' .
                            Yii::t('app', 'tooman') .
                            "</span>";
                    }
                    ?>
                </div>
                <div class="detail-row desc">
                    <span class="body1"><?= Tools::changeToFa($model->description); ?></span>
                </div>
            </section>
            <section>
                <div class="detail-row features ">
                    <div><span class="subtitle1"><?= Yii::t('app', 'features') ?></span></div>
                    <div class=" h-list">
                        <?php
                        $features = $model->getFeatures();
                        if (isset($features)) {
                            $featureList = $features->all();
                            foreach ($featureList as $feature) {
                                echo "<span class='body1'>" . Tools::changeToFa($feature->title) . "</span>";
                            }
                        }
                        ?>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>