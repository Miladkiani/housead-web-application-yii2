<?php

use yii\db\Migration;

/**
 * Class m200219_081038_add_god_chief
 */
class m200219_081038_add_god_chief extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('user',
            [
                'id'=>1,
                'first_name'=>'مدیر',
                'last_name'=>'ارشد',
                'username'=>'admin',
                'auth_key'=>Yii::$app->security->generateRandomString(),
                'password_hash'=>Yii::$app->security->generatePasswordHash('12345678'),
                'email'=>'admin@email.com',
                'phone'=>'02020202'
            ]
        );
        $this->insert('auth_assignment',
            [
                'item_name'=>'chief',
                'user_id'=>1
            ]
        );

        $this->batchInsert('category',['title','created_at','updated_at','author_id'],[
            ['آپارتمانی',time(),time(),1],
            ['ویلایی',time(),time(),1],
            ['تجاری',time(),time(),1],
            ['کشاورزی',time(),time(),1],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('category');
        $this->delete('auth_assignment',['user_id'=>1]);
        $this->delete('user',['id'=>1]);
        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200127_071637_add_god_chief cannot be reverted.\n";

        return false;
    }
    */
}
