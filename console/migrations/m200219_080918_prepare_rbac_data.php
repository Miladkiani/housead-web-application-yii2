<?php

use yii\db\Migration;

/**
 * Class m200219_080918_prepare_rbac_data
 */
class m200219_080918_prepare_rbac_data extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->alterColumn('auth_assignment','user_id','integer');

        $auth = Yii::$app->authManager;

        $createHouse = $auth->createPermission('createHouse');
        $createHouse->description = 'user can access to createHouse';
        $auth->add($createHouse);

        $activeHouse = $auth->createPermission('activeHouse');
        $activeHouse->description = 'user can active or inactive house';
        $auth->add($activeHouse);

        $updateHouse = $auth->createPermission('updateHouse');
        $updateHouse->description = 'user can access to update the house';
        $auth->add($updateHouse);

        $deleteHouse = $auth->createPermission('deleteHouse');
        $deleteHouse->description = 'user can access to delete the house';
        $auth->add($deleteHouse);

        $createNeighborhood = $auth->createPermission('createNeighborhood');
        $createNeighborhood->description = 'user can access to createNeighborhood';
        $auth->add($createNeighborhood);

        $updateNeighborhood = $auth->createPermission('updateNeighborhood');
        $updateNeighborhood->description = 'user can access to updateNeighborhood';
        $auth->add($updateNeighborhood);

        $deleteNeighborhood = $auth->createPermission('deleteNeighborhood');
        $deleteNeighborhood->description = 'user can access to deleteNeighborhood';
        $auth->add($deleteNeighborhood);

        $createFeature = $auth->createPermission('createFeature');
        $createFeature->description = 'user can access to createFeature';
        $auth->add($createFeature);

        $updateFeature = $auth->createPermission('updateFeature');
        $updateFeature->description = 'user can access to update the feature';
        $auth->add($updateFeature);

        $deleteFeature = $auth->createPermission('deleteFeature');
        $deleteFeature->description = 'user can access to delete the feature';
        $auth->add($deleteFeature);

        $createCategory = $auth->createPermission('createCategory');
        $createCategory->description = 'user can access to createCategory';
        $auth->add($createCategory);

        $updateCategory = $auth->createPermission('updateCategory');
        $updateCategory->description = 'user can access to update the category';
        $auth->add($updateCategory);

        $deleteCategory = $auth->createPermission('deleteCategory');
        $deleteCategory->description = 'user can access to delete the category';
        $auth->add($deleteCategory);

        $employee = $auth->createRole('employee');
        $employee->description =
            'this user only can create House, Neighborhood, feature , Category and update own records';
        $auth->add($employee);
        $auth->addChild($employee,$createHouse);
        $auth->addChild($employee,$createNeighborhood);
        $auth->addChild($employee,$createFeature);
        $auth->addChild($employee,$createCategory);


        $admin = $auth->createRole('admin');
        $admin->description=
            'this user can create records and active status';
        $auth->add($admin);
        $auth->addChild($admin,$employee);

        $chief = $auth->createRole('chief');
        $chief->description = 'this user can access all around of panel';
        $auth->add($chief);

        $auth->addChild($chief,$activeHouse);
        $auth->addChild($chief,$updateHouse);
        $auth->addChild($chief,$deleteHouse);
        $auth->addChild($chief,$updateFeature);
        $auth->addChild($chief,$deleteFeature);
        $auth->addChild($chief,$updateNeighborhood);
        $auth->addChild($chief,$deleteNeighborhood);
        $auth->addChild($chief,$updateCategory);
        $auth->addChild($chief,$deleteCategory);
        $auth->addChild($chief,$admin);

        // add the rule
        $authorRule = new \backend\rbac\AuthorRule();
        $auth->add($authorRule);


        $updateOwnPost =  $auth->createPermission('updateOwnPost');
        $updateOwnPost->description = "change own post";
        $updateOwnPost->ruleName = $authorRule->name;

        $auth->add($updateOwnPost);

        $auth->addChild($updateOwnPost,$updateHouse);
        $auth->addChild($updateOwnPost,$updateFeature);
        $auth->addChild($updateOwnPost,$updateNeighborhood);
        $auth->addChild($updateOwnPost,$updateCategory);

        $auth->addChild($employee,$updateOwnPost);

        $adminRule = new \backend\rbac\AuthorOrByEmployeeRule();
        $auth->add($adminRule);

        $updateAdminPost = $auth->createPermission('updateAdminPost');
        $updateAdminPost->description = 'change own and employee';
        $updateAdminPost->ruleName = $adminRule->name;
        $auth->add($updateAdminPost);

        $deleteAdminPost = $auth->createPermission('deleteAdminPost');
        $deleteAdminPost->description = 'delete own and employee';
        $deleteAdminPost->ruleName = $adminRule->name;
        $auth->add($deleteAdminPost);

        $activeAdminHouse = $auth->createPermission('activeAdminHouse');
        $activeAdminHouse->description = 'active or inactive own and employee';
        $activeAdminHouse->ruleName = $adminRule->name;
        $auth->add($activeAdminHouse);

        $auth->addChild($updateAdminPost,$updateHouse);
        $auth->addChild($updateAdminPost,$updateFeature);
        $auth->addChild($updateAdminPost,$updateNeighborhood);
        $auth->addChild($updateAdminPost,$updateCategory);

        $auth->addChild($deleteAdminPost,$deleteHouse);
        $auth->addChild($deleteAdminPost,$deleteFeature);
        $auth->addChild($deleteAdminPost,$deleteNeighborhood);
        $auth->addChild($deleteAdminPost,$deleteCategory);

        $auth->addChild($activeAdminHouse,$activeHouse);

        $auth->addChild($admin,$updateAdminPost);
        $auth->addChild($admin,$deleteAdminPost);
        $auth->addChild($admin,$activeAdminHouse);
    }

    public function safeDown()
    {
        echo "m200104_145058_init_rbac be reverted.\n";
        $auth = Yii::$app->authManager;
        $auth->removeAll();

    }
}
