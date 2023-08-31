<?php

use yii\db\Migration;

/**
 * Class m200325_143816_insert_permission_for_notification
 */
class m200325_143816_insert_permission_for_notification extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $auth = Yii::$app->authManager;

        $createNotification = $auth->createPermission('createNotification');
        $createNotification->description = 'user can access to create notification';
        $auth->add($createNotification);

        $sendNotification = $auth->createPermission('sendNotification');
        $sendNotification->description = 'user can send notification';
        $auth->add($sendNotification);

        $updateNotification = $auth->createPermission('updateNotification');
        $updateNotification->description = 'user can access to update notification';
        $auth->add($updateNotification);

        $deleteNotification = $auth->createPermission('deleteNotification');
        $deleteNotification->description = 'user can access to delete notification';
        $auth->add($deleteNotification);

        $admin = $auth->getRole('admin');
        $auth->addChild($admin,$createNotification);

        $chief = $auth->getRole('chief');

        $auth->addChild($chief,$sendNotification);
        $auth->addChild($chief,$deleteNotification);
        $auth->addChild($chief,$updateNotification);

        $updateAdminPost = $auth->getPermission('updateAdminPost');
        $auth->addChild($updateAdminPost,$updateNotification);

        $deleteAdminPost = $auth->getPermission('deleteAdminPost');
        $auth->addChild($deleteAdminPost,$deleteNotification);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $auth = Yii::$app->authManager;
        $deleteNotification = $auth->getPermission('deleteNotification');
        $createNotification = $auth->getPermission('createNotification');
        $updateNotification = $auth->getPermission('updateNotification');
        $sendNotification = $auth->getPermission('sendNotification');

        $deleteAdminPost = $auth->getPermission('deleteAdminPost');
        $auth->removeChild($deleteAdminPost,$deleteNotification);

        $updateAdminPost = $auth->getPermission('updateAdminPost');
        $auth->removeChild($updateAdminPost,$updateNotification);

        $admin = $auth->getRole('admin');
        $auth->removeChild($admin,$createNotification);

        $chief = $auth->getRole('chief');

        $auth->removeChild($chief,$sendNotification);
        $auth->removeChild($chief,$deleteNotification);
        $auth->removeChild($chief,$updateNotification);

        $auth->remove($createNotification);
        $auth->remove($deleteNotification);
        $auth->remove($updateNotification);
        $auth->remove($sendNotification);

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200325_143816_insert_permission_for_notification cannot be reverted.\n";

        return false;
    }
    */
}
