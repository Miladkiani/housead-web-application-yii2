<?php

use backend\models\Admin;
use yii\helpers\Html;
use yii\helpers\Url;

?>
<div class="navigation" id="sidebar">

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
    <nav>
        <?= Html::a('<i class="fa fa-home"></i>' . ' '
            . Yii::t('app', 'houses'), ['house/index'],
            ['class' => ('house' == Yii::$app->controller->id)?'active':'']
        ); ?>

        <?= Html::a('<i class="fa fa-layer-group"></i>' . ' '
            . Yii::t('app', 'categories'), ['category/index'],
            ['class' => ('category' == Yii::$app->controller->id)?'active':'']
        ); ?>

        <?= Html::a('<i class="fa fa-star"></i>' . ' '
            . Yii::t('app', 'features'), ['feature/index'],
            ['class' => ('feature' == Yii::$app->controller->id)?'active':'']
        ); ?>

        <?= Html::a('<i class="fa fa-globe"></i>' . ' '
            . Yii::t('app', 'neighborhoods'), ['neighborhood/index'],
            ['class' => ('neighborhood' == Yii::$app->controller->id)?'active':'']
        ); ?>

        <?php
        if (Yii::$app->user->can('admin')) {
            echo   Html::a('<i class="fa fa-bell"></i>' . ' ' .
                Yii::t('app', 'notifications'), ['notification/index'],
                ['class' => ('notification' == Yii::$app->controller->id)?'active':'']
            );
        }
        ?>

        <?php
        if (Yii::$app->user->can('chief')) {
         echo   Html::a('<i class="fa fa-users"></i>' . ' ' .
                Yii::t('app', 'users'), ['admin/index'],
                ['class' => ('admin' == Yii::$app->controller->id &&
                    Yii::$app->controller->action->id != 'view') ? 'active' : '']
            );
        }
        ?>

        <?= Html::a('<i class="fa fa-user-tie"></i>' . ' 
                    ' . Yii::t('app', 'my_profile'), ['admin/view'],
            ['class' =>
                ('admin' == Yii::$app->controller->id &&
                    Yii::$app->controller->action->id == 'view') ? 'active' : '']
        );

        ?>
        <?php echo '<a class="logout"><i class="fa fa-sign-out-alt"></i>';
        echo Html::beginForm(['/site/logout'], 'post')
            . Html::submitButton(' ' .
                Yii::t('app', 'logout'),
                ['class' => 'btn btn-link logout']
            )
            . Html::endForm();
        echo "</a>";
        ?>
    </nav>
</div>
