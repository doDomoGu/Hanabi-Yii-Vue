<?php

namespace app\models;


use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "game".
 *
 * @property integer $id
 * @property integer $room_id
 * @property integer $master_player_user_id
 * @property integer $guest_player_user_id
 * @property integer $round_num
 * @property integer $round_player
 * @property integer $cue_num
 * @property integer $chance_num
 * @property integer $status
 * @property integer $score
 * @property string $created_at
 * @property string $updated_at
 */
class Game extends ActiveRecord
{
    const DEFAULT_CUE = 8;   //默认提供线索(CUE)次数
    const DEFAULT_CHANCE = 3;  //默认可燃放机会(chance)次数

    public static $cue_types = ['color','num'];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'game';
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
            [['room_id', 'master_player_user_id', 'guest_player_user_id', 'round_num', 'round_player'], 'required'],
            [['room_id', 'master_player_user_id', 'guest_player_user_id', 'round_num', 'round_player', 'cue_num', 'chance_num','status','score'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
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
            'master_player_user_id'=>'1P玩家的ID',
            'guest_player_user_id'=>'2P玩家的ID',
            'round_num' => '当前回合数',
            'round_player' => '当前回合对应的玩家', //1或2 对应1P 2P
            'cue_num' => '剩余提示次数',
            'chance_num' => '剩余燃放机会次数',
            'status' => 'Status',  //1:游玩中,2:结束
            'score' => '分数',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public static function createOne($room_id){
        $game_id = false;
        $room = Room::find()->where(['id'=>$room_id,'status'=>Room::STATUS_PREPARING])->one();
        if($room){
            $roomUser = RoomUser::find()->where(['room_id'=>$room->id])->all();
            $roomUserCount = count($roomUser);
            if($roomUserCount==2){
                $masterFlag = false;
                $masterId = 0;
                $guestFlag = false;
                $guestId = 0;
                foreach($roomUser as $u){
                    if($u->role_type==RoomUser::ROLE_TYPE_MASTER){
                        $masterFlag = true;
                        $masterId = $u->user_id;
                    }elseif($u->role_type==RoomUser::ROLE_TYPE_GUEST){
                        if($u->is_ready){
                            $guestFlag = true;
                            $guestId = $u->user_id;
                        }
                    }
                }
                //检测是否是"准备完成"状态
                if($masterFlag && $guestFlag){
                    $game = new Game();
                    $game->room_id = $room->id;
                    $game->master_player_user_id = $masterId;
                    $game->guest_player_user_id = $guestId;
                    $game->round_num = 1;
                    $game->round_player = rand(1,2); //随机选择一个玩家开始第一个回合
                    $game->cue_num = self::DEFAULT_CUE;
                    $game->chance_num = self::DEFAULT_CHANCE;
                    $game->status = 1;
                    $game->score = 0;
                    if($game->save()){
                        if(GameCard::initLibrary($game->id)){
                            for($i=0;$i<5;$i++){ //玩家 1 2 各模五张牌
                                GameCard::drawCard($game->id,1);
                                GameCard::drawCard($game->id,2);
                            }
                            $game_id = $game->id;
                        }
                    }else{
                        echo 11;exit;
                        //TODO 错误处理
                    }
                }else{
                    echo 22;exit;
                    //TODO 错误处理
                }
            }else{
                echo 33;exit;
                //TODO 错误处理
            }
        }else{
            echo 44;exit;
            //TODO 错误处理
        }
        return $game_id;
    }
}
