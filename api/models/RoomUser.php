<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
/**
 * This is the model class for table "room_user".
 *
 * @property integer $id
 * @property integer $room_id
 * @property integer $user_id
 * @property integer $role_type
 * @property integer $is_ready
 * @property string $created_at
 * @property string $updated_at
 */
class RoomUser extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'room_user';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value' => new Expression('NOW()'),  //时间戳（数字型）转为 日期字符串
                //'value'=>$this->timeTemp(),
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['room_id', 'user_id', 'role_type', 'is_ready'], 'required'],
            [['room_id', 'user_id', 'role_type', 'is_ready'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['room_id', 'user_id'], 'unique', 'targetAttribute' => ['room_id', 'user_id'], 'message' => 'The combination of Room ID and User ID has already been taken.'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'room_id' => 'Room ID',
            'user_id' => 'User ID',
            'role_type' => 'Role Type',
            'is_ready' => 'Is Ready',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
