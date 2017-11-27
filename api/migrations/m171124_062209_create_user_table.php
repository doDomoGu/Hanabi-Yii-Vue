<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user`.
 */
class m171124_062209_create_user_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=MyISAM';
        }

        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull()->unique(),
            'password' => $this->string()->notNull(),

            'nickname' => $this->string()->notNull(),
            'mobile' => $this->string()->notNull()->unique(),
            'email' => $this->string()->notNull()->unique(),
            'avatar' => $this->string(),
            'gender' => $this->smallInteger()->notNull()->defaultValue(0),
            'birthday' => $this->date(),

            //'password_reset_token' => $this->string()->unique(),
            'register_time' => $this->dateTime()->notNull(),
            'status' => $this->smallInteger()->notNull()->defaultValue(1),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%user}}');
    }
}
