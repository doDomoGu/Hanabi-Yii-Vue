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


    const STATUS_PLAYING = 1;
    const STATUS_END = 2;

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


    public static function start(){
        $success = false;
        $msg = '';
        $game_id = 0;
        $user_id = Yii::$app->user->id;
        $userRoomUser = RoomUser::find()->where(['user_id'=>$user_id])->all();
        if(count($userRoomUser) == 1){
            //只有玩家1可以进行"开始游戏操作"
            if($userRoomUser[0]->role_type==RoomUser::ROLE_TYPE_MASTER){
                $room = Room::find()->where(['id'=>$userRoomUser[0]->room_id])->one();
                if($room){
                    if($room->status==Room::STATUS_PREPARING){
                        $roomUser = RoomUser::find()->where(['room_id'=>$room->id])->all();
                        if(count($roomUser)>2){
                            $msg = '房间中人数大于2，数据错误';
                        }else if(count($roomUser)==2){
                            foreach($roomUser as $u){
                                if($u->role_type == RoomUser::ROLE_TYPE_GUEST) {
                                    if ($u->is_ready == 1) {
                                        //新建Game
                                        $game_id = self::createOne($room->id);
                                        if($game_id){
                                            $room->status = Room::STATUS_PLAYING;
                                            if ($room->save()) {
                                                $success = true;
                                                $msg = '开始游戏成功';
                                            }
                                        }
                                    }
                                }
                            }
                            if($success==false){
                                $msg = '未知错误001';
                            }
                        }else{
                            $msg = '房间中人数不等于2，数据错误';
                        }
                    }else{
                        $msg = '房间状态不是"准备中"！(STATUS_PREPARING)';
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

        return [$success,$msg,$game_id];
    }

    private static function createOne($room_id){
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
                    $game->status = Game::STATUS_PLAYING;
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


    public static function end(){
        $success = false;
        $msg = '';
        $user_id = Yii::$app->user->id;
        $userRoomUser = RoomUser::find()->where(['user_id'=>$user_id])->all();
        if(count($userRoomUser) == 1){
            //TODO 暂时只有玩家1可以进行"开始游戏操作"
            if($userRoomUser[0]->role_type==RoomUser::ROLE_TYPE_MASTER){
                $room = Room::find()->where(['id'=>$userRoomUser[0]->room_id])->one();
                if($room){
                    if($room->status==Room::STATUS_PLAYING){
                        $userGame = Game::find()->where(['room_id'=>$room->id,'status'=>Game::STATUS_PLAYING])->all();
                        if(count($userGame)==1) {
                            $game = $userGame[0];
                            // 1.修改游戏为结束状态
                            $game->status = Game::STATUS_END;
                            $game->save();
                            // 2.修改房间为准备状态
                            $room->status = Room::STATUS_PREPARING;
                            $room->save();
                            // 3.修改玩家2状态为"未准备"
                            $guest_user = RoomUser::find()->where(['room_id'=>$room->id,'role_type'=>RoomUser::ROLE_TYPE_GUEST])->one();
                            if($guest_user){
                                $guest_user->is_ready = 0;
                                $guest_user->save();
                            }
                            $success = true;
                        }else{
                            $msg = '你所在房间游戏未开始/或者有多个游戏，错误';
                        }
                    }else{
                        $msg = '房间状态不是"游玩中"！(STATUS_PREPARING)';
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

    public static function isInGame(){
        $success = false;
        $msg = '';
        $game_id = 0;
        $user_id = Yii::$app->user->id;
        $userRoomUser = RoomUser::find()->where(['user_id'=>$user_id])->all();
        if(count($userRoomUser)==1){
            $room_id = $userRoomUser[0]->room_id;
            $userGame = Game::find()->where(['room_id'=>$room_id,'status'=>Game::STATUS_PLAYING])->all();
            if(count($userGame)==1) {
                $game_id = $userGame[0]->id;
                $success = true;
            }else{
                $msg = '你所在房间游戏未开始/或者有多个游戏，错误';
            }
        }elseif(count($userRoomUser)==0) {
            $msg = '不在房间中了';
        }else{
            $msg = '在多个房间中，数据错误';
        }

        return [$success,$msg,['game_id'=>$game_id]];
    }

    public static function getInfo(){
        $success = false;
        $msg = '';
        $data = [];
        $user_id = Yii::$app->user->id;
        $userRoomUser = RoomUser::find()->where(['user_id'=>$user_id])->all();
        if(count($userRoomUser) == 1){
            $userGame = Game::find()->where(['room_id'=>$userRoomUser[0]->room_id,'status'=>Game::STATUS_PLAYING])->all();
            if(count($userGame)==1){
                $game = $userGame[0];
                $gameCardCount = GameCard::find()->where(['game_id'=>$game->id])->count();
                if($gameCardCount==Card::CARD_NUM_ALL){
                    $cardInfo = self::getCardInfo($game->id);


                    $data['card'] = $cardInfo;
                    $success = true;
                }else{
                    $msg = '总卡牌数错误';
                }
            }else{
                $msg = '你所在房间游戏未开始/或者有多个游戏，错误';
            }
        }else{
            $msg = '你不在房间中/不止在一个房间中，错误';
        }

        return [$success,$msg,$data];
    }

    private static function getCardInfo($game_id){
        $data = [];
        $masterCard = GameCard::find()->where(['game_id'=>$game_id,'type'=>GameCard::TYPE_IN_PLAYER,'player_num'=>1])->orderBy('type_ord asc')->all();
        $guestCard = GameCard::find()->where(['game_id'=>$game_id,'type'=>GameCard::TYPE_IN_PLAYER,'player_num'=>2])->orderBy('type_ord asc')->all();
        $libraryCard = GameCard::find()->where(['game_id'=>$game_id,'type'=>GameCard::TYPE_IN_LIBRARY])->orderBy('type_ord asc')->all();
        $tableCard = GameCard::find()->where(['game_id'=>$game_id,'type'=>GameCard::TYPE_ON_TABLE])->orderBy('type_ord asc')->all();
        $discardCard = GameCard::find()->where(['game_id'=>$game_id,'type'=>GameCard::TYPE_IN_DISCARD])->orderBy('type_ord asc')->all();

        $master_hands = [];
        foreach($masterCard as $card){
            $cardArr = [
                'color'=>$card->color,
                'num'=>$card->num
            ];
            $master_hands[] = $cardArr;
        }

        $data['master_hands'] = $master_hands;

        $guest_hands = [];
        foreach($guestCard as $card){
            $cardArr = [
                'color'=>$card->color,
                'num'=>$card->num
            ];
            $guest_hands[] = $cardArr;
        }

        $data['guest_hands'] = $guest_hands;

        return $data;
    }
}
