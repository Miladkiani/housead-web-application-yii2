<?php
use yii\helpers\Html;
use yii\helpers\Url;
use backend\models\Admin;
?>
<div class="nav-top" id="topnav">
    <div class="nav-icon" id="nav-icon">
        <span></span>
        <span></span>
        <span></span>
    </div>
    <div class="profile-summary">
        <?php
        $path = Url::to('@web/images/avatar.png');
        $id = Yii::$app->user->identity->getId();
        $profile = Admin::findOne($id);
        $avatar = $profile->avatar;
        if (isset($avatar) && !empty($avatar)) {
            $path = Yii::$app->request->baseUrl .
                Yii::getAlias('@userUploadUrl') .
                '/' . Html::encode($avatar->image_web_filename);
        }
        echo "<div class='avatar-panel'>";
        echo Html::img(
                $path,
            [
                    'class' => 'avatar',
                'alt' => Yii::t('app', 'avatar')
            ]
        );
        echo "<div class='avatar-info'>";
        echo '<a>'.Html::encode($profile->username).'</a>';
        echo '<br>';
        echo '<a>'.Html::encode($profile->first_name).
            ' ' .Html::encode($profile->last_name).'</a>';
        echo '</div>';
        echo '</div>';
        ?>
    </div>
    <div class ="logo" >
        <?= '<a href="'.Yii::$app->request->baseUrl.'"><i class="fa fa-location-arrow"></i></a>'?>
    </div>
</div>
