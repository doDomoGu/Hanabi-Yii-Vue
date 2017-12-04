<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "room".
 *
 * @property integer $id
 * @property string $title
 * @property string $password
 * @property string $edit_time
 * @property integer $status
 */
class Room extends \yii\db\ActiveRecord
{
    const STATUS_AVAILABLE = 0;  //可用的空房间
    const STATUS_PREPARING = 1;  //准备中，未开始
    const STATUS_PLAYING = 2;    //游玩中，已开始


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'room';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['edit_time'], 'safe'],
            [['status'], 'integer'],
            [['title'], 'string', 'max' => 100],
            [['password'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'password' => 'Password',
            'edit_time' => 'Edit Time',
            'status' => 'Status',
        ];
    }



    public static function enter($room_id,$user_id){
        $success = false;
        $msg = '';
        $userRoomUser = RoomUser::find()->where(['user_id'=>$user_id])->all();
        if(count($userRoomUser)==1){
           $msg = '已经在房间中了';
        }elseif(count($userRoomUser)==0) {
            $room = Room::find()->where(['id' => $room_id])->one();
            if ($room) {
                if ($room->password != '') {
                    $msg = '房间被锁住了';
                } else {
                    $room_user = RoomUser::find()->where(['room_id' => $room->id])->andWhere(['in', 'role_type', [1, 2]])->all();
                    $room_user_count = count($room_user);
                    if ($room_user_count<2){
                        if ($room_user_count === 0) {
                            $new_room_user = new RoomUser();
                            $new_room_user->room_id = $room->id;
                            $new_room_user->user_id = $user_id;
                            $new_room_user->role_type = RoomUser::ROLE_TYPE_MASTER;
                            $new_room_user->is_ready = 0;
                            $new_room_user->save();
                        }else if ($room_user_count === 1) {
                            $new_room_user = new RoomUser();
                            $new_room_user->room_id = $room->id;
                            $new_room_user->user_id = $user_id;
                            $new_room_user->role_type = RoomUser::ROLE_TYPE_GUEST;
                            $new_room_user->is_ready = 0;
                            $new_room_user->save();
                        }
                        if($room->status == self::STATUS_AVAILABLE){
                            $room->status = self::STATUS_PREPARING;
                            $room->save();
                        }
                        $success = true;
                        $msg = '房间进入成功';
                    }else if($room_user_count>2){
                        $msg = '房间人数多于两个，错误！';
                    }else{
                        $msg = '房间已满';
                    }
                }
            } else {
                $msg = '房间号错误';
            }
        }else{
            $msg = '数据错误，在多个房间中!!';
        }

        return array($success,$msg);
    }
}
