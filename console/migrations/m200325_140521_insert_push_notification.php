<?php

use yii\db\Migration;

/**
 * Class m200325_140521_insert_push_notification
 */
class m200325_140521_insert_push_notification extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%notification}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(30)->notNull(),
            'small_body' => $this->string(150)->notNull(),
            'big_body' => $this->text(),
            'color' => $this->string(9),
            'time_to_live'=>$this->integer()->notNull(),
            'status' => $this->smallInteger()->notNull()->defaultValue(9),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'author_id'=>$this->integer(),
        ], $tableOptions);

        $this->createIndex(
            'idx-notification-author_id',
            'notification',
            'author_id');

        $this->addForeignKey(
            'fk-notification-author_id',
            'notification',
            'author_id',
            'user',
            'id'
        );

        $this->createTable('{{%small_icon}}', [
            'id' => $this->primaryKey(),
            'image_src_filename' => $this->string(255)->notNull(),
            'image_web_filename' => $this->string(255)->unique(),
            'notification_id'=>$this->integer(),
        ], $tableOptions);

        $this->createIndex(
            'idx-small_icon-notification_id',
            'small_icon',
            'notification_id'
        );
        $this->addForeignKey(
            'fk-small_icon-notification_id',
            'small_icon',
            'notification_id',
            'notification',
            'id'
        );

        $this->createTable('{{%large_icon}}', [
            'id' => $this->primaryKey(),
            'image_src_filename' => $this->string(255)->notNull(),
            'image_web_filename' => $this->string(255)->unique(),
            'notification_id'=>$this->integer(),
        ], $tableOptions);

        $this->createIndex(
            'idx-large_icon-notification_id',
            'large_icon',
            'notification_id'
        );
        $this->addForeignKey(
            'fk-large_icon-notification_id',
            'large_icon',
            'notification_id',
            'notification',
            'id'
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-small_icon-notification_id',
            'small_icon');
        $this->dropTable('small_icon');

        $this->dropForeignKey(
            'fk-large_icon-notification_id',
            'large_icon');
        $this->dropTable('large_icon');

        $this->dropForeignKey(
            'fk-notification-author_id',
            'notification');
        $this->dropTable('notification');

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200325_140521_insert_push_notification cannot be reverted.\n";

        return false;
    }
    */
}
