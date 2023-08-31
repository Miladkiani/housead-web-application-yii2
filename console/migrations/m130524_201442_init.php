<?php

use yii\db\Migration;

class m130524_201442_init extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'first_name' => $this->string(25)->notNull(),
            'last_name' => $this->string(25)->notNull(),
            'username' => $this->string(16)->notNull()->unique(),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string()->unique(),
            'email' => $this->string()->notNull()->unique(),
            'phone' => $this->string(15)->notNull(),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'last_login_time'=>$this->integer(),
            'last_login_ip'=>$this->string(24)
        ], $tableOptions);

        $this->createTable('{{%avatar}}', [
            'id' => $this->primaryKey(),
            'image_src_filename' => $this->string(255)->notNull(),
            'image_web_filename' => $this->string(255)->notNull()->unique(),
            'user_id' => $this->integer()->notNull()->unique()
        ], $tableOptions);


        $this->addForeignKey(
            'fk-avatar-user_id',
            'avatar',
            'user_id',
            'user',
            'id',
            'CASCADE');

        $this->createTable('{{%state}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(30)->notNull()->unique(),
        ], $tableOptions);


        $this->createTable('{{%city}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(30)->notNull(),
            'state_id' => $this->integer()->notNull()
        ], $tableOptions);

        $this->createIndex(
            'idx-city-state_id', 'city', 'state_id');
        $this->addForeignKey(
            'fk-city-state_id',
            'city',
            'state_id',
            'state',
            'id',
            'CASCADE');

        $this->createTable('{{%neighborhood}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(30)->notNull(),
            'city_id' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'author_id' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createIndex('idx-neighborhood-city_id',
            'neighborhood', 'city_id');
        $this->addForeignKey(
            'fk-neighborhood-city_id',
            'neighborhood',
            'city_id',
            'city',
            'id',
            'CASCADE');
        $this->createIndex('idx-neighborhood-author_id', 'neighborhood', 'author_id');

        $this->addForeignKey(
            'fk-neighborhood-author_id',
            'neighborhood',
            'author_id',
            'user',
            'id');

        $this->createTable('{{%category}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(30)->notNull()->unique(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'author_id' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createIndex(
            'idx-category-author_id',
            'category',
            'author_id');
        $this->addForeignKey(
            'fk-category-author_id',
            'category',
            'author_id',
            'user',
            'id');

        $this->createTable('{{%house}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(50)->notNull(),
            'description' => $this->text(),
            'category_id' => $this->integer()->notNull(),
            'neighborhood_id' => $this->integer()->notNull(),
            'address' => $this->text()->notNull(),
            'postcode' => $this->string(13),
            'phone' => $this->string(15)->notNull(),
            'size' => $this->integer()->notNull(),
            'room' => $this->smallInteger()->defaultValue(0),
            'furniture' => $this->smallInteger()->defaultValue(0),
            'lease_type' => $this->smallInteger()->notNull(),
            'sell' => $this->bigInteger(),
            'prepayment' => $this->bigInteger(),
            'rent' => $this->bigInteger(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'author_id' => $this->integer()->notNull(),
            'status' => $this->smallInteger()->notNull()->defaultValue(9)
        ], $tableOptions);

        $this->createIndex('idx-house-lease_type', 'house', 'lease_type');
        $this->createIndex('idx-house-category_id', 'house', 'category_id');
        $this->createIndex('idx-house-neighborhood_id', 'house', 'neighborhood_id');
        $this->createIndex('idx-house-author_id', 'house', 'author_id');
        $this->addForeignKey(
            'fk-house-category_id',
            'house',
            'category_id',
            'category',
            'id');

        $this->addForeignKey(
            'fk-house-neighborhood_id',
            'house',
            'neighborhood_id',
            'neighborhood',
            'id');

        $this->addForeignKey(
            'fk-house-author_id',
            'house',
            'author_id',
            'user',
            'id');

        $this->createTable('{{%feature}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string('30')->notNull()->unique(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'author_id' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createIndex('idx-feature-author_id','feature','author_id');
        $this->addForeignKey(
            'fk-feature-author_id',
            'feature',
            'author_id',
            'user',
            'id');

        $this->createTable('{{%house_feature}}', [
            'house_id' => $this->integer()->notNull(),
            'feature_id' => $this->integer()->notNull(),
            'PRIMARY KEY(house_id,feature_id)'
        ], $tableOptions);

        $this->createIndex('idx-house_feature-house_id', 'house_feature', 'house_id');
        $this->createIndex('idx-house_feature-feature_id', 'house_feature', 'feature_id');

        $this->addForeignKey(
            'fk-house_feature-house_id',
            'house_feature', 'house_id',
            'house', 'id',
            'CASCADE');

        $this->addForeignKey(
            'fk-house_feature-feature_id',
            'house_feature', 'feature_id',
            'feature', 'id',
            'CASCADE');

        $this->createTable('{{%feature_category}}', [
            'feature_id' => $this->integer()->notNull(),
            'category_id' => $this->integer()->notNull(),
            'PRIMARY KEY(feature_id,category_id)'
        ]);

        $this->createIndex('idx-feature_category-feature_id', 'feature_category', 'feature_id');
        $this->createIndex('idx-feature_category-category_id', 'feature_category', 'category_id');

        $this->addForeignKey(
            'fk-feature_category-feature_id',
            'feature_category', 'feature_id',
            'feature', 'id',
            'CASCADE');

        $this->addForeignKey(
            'fk-feature_category-category_id',
            'feature_category', 'category_id',
            'category', 'id',
            'CASCADE');

        $this->createTable('{{%gallery}}', [
            'id' => $this->primaryKey(),
            'image_src_filename' => $this->string(255)->notNull(),
            'image_web_filename' => $this->string(255)->notNull()->unique(),
            'house_id' => $this->integer()->notNull()
        ], $tableOptions);

        $this->createIndex('idx-gallery-house_id', 'gallery', 'house_id');
        $this->addForeignKey(
            'fk-gallery-house_id',
            'gallery',
            'house_id',
            'house',
            'id',
            'CASCADE');
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-gallery-house_id', 'gallery');
        $this->dropIndex('idx-gallery-house_id', 'gallery');

        $this->dropTable('{{%gallery}}');

        $this->dropForeignKey('fk-feature_category-feature_id', 'feature_category');
        $this->dropIndex('idx-feature_category-feature_id', 'feature_category');

        $this->dropForeignKey('fk-feature_category-category_id', 'feature_category');
        $this->dropIndex('idx-feature_category-category_id', 'feature_category');

        $this->dropTable('{{%feature_category}}');

        $this->dropForeignKey('fk-house_feature-house_id', 'house_feature');
        $this->dropIndex('idx-house_feature-house_id', 'house_feature');

        $this->dropForeignKey('fk-house_feature-feature_id', 'house_feature');
        $this->dropIndex('idx-house_feature-feature_id', 'house_feature');

        $this->dropTable('{{%house_feature}}');

        $this->dropForeignKey('fk-feature-author_id','feature');
        $this->dropIndex('idx-feature-author_id','feature');

        $this->dropTable('{{%feature}}');

        $this->dropForeignKey('fk-house-neighborhood_id', 'house');
        $this->dropIndex('idx-house-neighborhood_id', 'house');

        $this->dropForeignKey('fk-house-category_id', 'house');
        $this->dropIndex('idx-house-category_id', 'house');

        $this->dropForeignKey('fk-house-author_id', 'house');
        $this->dropIndex('idx-house-author_id', 'house');

        $this->dropIndex('idx-house-lease_type', 'house');

        $this->dropTable('{{%house}}');

        $this->dropForeignKey('fk-category-author_id', 'category');
        $this->dropIndex('idx-category-author_id', 'category');
        $this->dropTable('{{%category}}');



        $this->dropForeignKey('fk-neighborhood-city_id', 'neighborhood');
        $this->dropIndex('idx-neighborhood-city_id', 'neighborhood');

        $this->dropForeignKey('fk-neighborhood-author_id', 'neighborhood');
        $this->dropIndex('idx-neighborhood-author_id', 'neighborhood');

        $this->dropTable('{{%neighborhood}}');

        $this->dropForeignKey('fk-city-state_id', 'city');
        $this->dropIndex('idx-city-state_id', 'city');

        $this->dropTable('{{%city}}');

        $this->dropTable('{{%state}}');

        $this->dropForeignKey('fk-avatar-user_id', 'avatar');

        $this->dropTable('{{%avatar}}');
        $this->dropTable('{{%user}}');
    }
}
