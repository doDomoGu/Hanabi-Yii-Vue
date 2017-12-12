<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
/**
 * This is the model class for table "room".
 *
 * @property integer $id
 * @property string $title
 * @property string $password
 * @property string $edit_time
 * @property integer $status
 */
class Room extends ActiveRecord
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



    public static function enter($room_id){
        $success = false;
        $msg = '';
        $user_id = Yii::$app->user->id;
        $userRoomUser = RoomUser::find()->where(['user_id'=>$user_id])->all();
        if(count($userRoomUser)==1){
           $msg = '已经在房间中了';
        }elseif(count($userRoomUser)==0) {
            $room = Room::find()->where(['id' => $room_id])->one();
            if ($room) {
                if($room->status == self::STATUS_PLAYING){
                    $msg = '房间已经开始游戏';
                }else if ($room->password != '') {
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
                        //$msg = '房间进入成功';
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

        return [$success,$msg];
    }

    public static function isInRoom(){
        $success = false;
        $msg = '';
        $room_id = 0;
        $user_id = Yii::$app->user->id;
        $userRoomUser = RoomUser::find()->where(['user_id'=>$user_id])->all();
        if(count($userRoomUser)==1){
            //$msg = '已经在房间中了';
            $room_id = $userRoomUser[0]->room_id;
            $success = true;
        }elseif(count($userRoomUser)==0) {
            $msg = '不在房间中了';
        }else{
            $msg = '在多个房间中，数据错误';
        }

        return [$success,$msg,['room_id'=>$room_id]];
    }


    public static function exitRoom(){
        $success = false;
        $msg = '';
        $user_id = Yii::$app->user->id;
        $userRoomUser = RoomUser::find()->where(['user_id'=>$user_id])->all();
        if(count($userRoomUser)==1){
            $roomUser = $userRoomUser[0];
            //room如果有其他玩家(2p,自己是1P) 要对应改变其状态
            if($roomUser->role_type==RoomUser::ROLE_TYPE_MASTER){
                $guestUser = RoomUser::find()->where(['room_id'=>$roomUser->room_id,'role_type'=>RoomUser::ROLE_TYPE_GUEST])->one();
                if($guestUser){
                    $guestUser->role_type = RoomUser::ROLE_TYPE_MASTER;
                    $guestUser->save();
                }else{
                    //没有2P 则要把房间状态 置位 空闲
                    $room = Room::find()->where(['id'=>$roomUser->room_id])->one();
                    $room->status = Room::STATUS_AVAILABLE;
                    $room->save();
                }
            }
            RoomUser::deleteAll(['user_id'=>$user_id]);
            $success = true;
        }elseif(count($userRoomUser)==0) {
            $msg = '不在房间中了';
        }else{
            $msg = '在多个房间中，数据错误';
        }

        return [$success,$msg];
    }


    public static function getInfo(){
        $success = false;
        $msg = '';
        $data = [
            'master_user'=>
            [
                'id'=>0,
                'username'=>'',
                'name'=>''
            ],
            'guest_user'=>
            [
                'id'=>0,
                'username'=>'',
                'name'=>'',
                'is_ready'=>false
            ],
            'is_playing'=>false
        ];
        $user_id = Yii::$app->user->id;
        $userRoomUser = RoomUser::find()->where(['user_id'=>$user_id])->all();
        if(count($userRoomUser) == 1){
            $room = Room::find()->where(['id'=>$userRoomUser[0]->room_id])->one();
            if($room){
                $roomUser = RoomUser::find()->where(['room_id'=>$room->id])->all();
                if(count($roomUser)>2){
                    $msg = '房间中人数大于2，数据错误';
                }else{
                    //$msg = '获取成功';
                    foreach($roomUser as $u){
                        if($u->role_type == RoomUser::ROLE_TYPE_MASTER){
                            $data['master_user'] = [
                                'id'=>$u->user->id,
                                'username'=>$u->user->username,
                                'name'=>$u->user->nickname,
                            ];
                        }elseif($u->role_type == RoomUser::ROLE_TYPE_GUEST){
                            $data['guest_user'] = [
                                'id'=>$u->user->id,
                                'username'=>$u->user->username,
                                'name'=>$u->user->nickname,
                                'is_ready'=>$u->is_ready==1?true:false
                            ];
                        }
                    }
                    $data['is_playing'] = $room->status == self::STATUS_PLAYING?true:false;

                    $success = true;
                }
            }else{
                $msg = '房间不存在！';
            }
        }else{
            $msg = '你不在房间中/不止在一个房间中，错误';
        }
        return [$success,$msg,$data];
    }

    public static function doReady(){
        $success = false;
        $msg = '';
        $user_id = Yii::$app->user->id;
        $userRoomUser = RoomUser::find()->where(['user_id'=>$user_id])->all();

        if(count($userRoomUser) == 1){
            //只有玩家2可以进行"准备操作"
            if($userRoomUser[0]->role_type==RoomUser::ROLE_TYPE_GUEST){
                $room = Room::find()->where(['id'=>$userRoomUser[0]->room_id])->one();
                if($room){
                    $roomUser = RoomUser::find()->where(['room_id'=>$room->id])->all();
                    if(count($roomUser)>2){
                        $msg = '房间中人数大于2，数据错误';
                    }else if(count($roomUser)==2){
                        foreach($roomUser as $u){
                            if($u->user_id==$user_id && $u->role_type == RoomUser::ROLE_TYPE_GUEST){
                                $u->is_ready = $u->is_ready==1?0:1;
                                if($u->save()){
                                    $success = true;
                                    $msg = $u->is_ready==1?'准备完成':'取消准备';
                                }
                            }
                        }
                        if($success==false){
                            $msg = '未知错误';
                        }
                    }else{
                        $msg = '房间中人数不等于2，数据错误';
                    }
                }else{
                    $msg = '房间不存在！';
                }
            }else{
                $msg = '玩家角色错误';
            }
        }else{
            $msg = '你不在房间中/不止在一个房间中，错误';
        }

        return [$success,$msg];
    }



}
