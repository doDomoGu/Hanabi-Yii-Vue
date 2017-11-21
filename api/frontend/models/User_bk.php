<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $username
 * @property string $password
 * @property string $name
 * @property string $mobile
 * @property string $email
 * @property int $status
 * @property int $verify
 */
class User_bk extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'password', 'name', 'mobile'], 'required'],
            [['status', 'verify'], 'integer'],
            [['username', 'password', 'name'], 'string', 'max' => 255],
            [['mobile'], 'string', 'max' => 20],
            [['email'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'password' => 'Password',
            'name' => 'Name',
            'mobile' => 'Mobile',
            'email' => 'Email',
            'status' => 'Status',
            'verify' => 'Verify',
        ];
    }
}
