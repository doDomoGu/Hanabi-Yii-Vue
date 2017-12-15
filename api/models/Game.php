<?php

namespace app\models;


use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "game".
 *
 * @property integer $room_id
 * @property integer $round_num
 * @property integer $round_player_is_host
 * @property integer $cue_num
 * @property integer $chance_num
 * @property integer $status
 * @property integer $score
 * @property string $updated_at
 */
class Game extends ActiveRecord
{
    const DEFAULT_CUE = 8;   //默认提供线索(CUE)次数
    const DEFAULT_CHANCE = 3;  //默认可燃放机会(chance)次数

    public static $cue_types = ['color','num'];


    const STATUS_PREPARING = 0;
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
            'room_id' => 'Room ID',
            'round_num' => '当前回合数',
            'round_player_is_host' => '当前回合对应的玩家', //是否是主机玩家
            'cue_num' => '剩余提示次数',
            'chance_num' => '剩余燃放机会次数',
            'status' => 'Status',  //1:游玩中,2:结束
            'score' => '分数',
            'updated_at' => 'Updated At',
        ];
    }


    public static function start(){
        $success = false;
        $msg = '';
        $game_id = 0;
        $user_id = Yii::$app->user->id;
        $userRoomUser = RoomPlayer::find()->where(['user_id'=>$user_id])->all();
        if(count($userRoomUser) == 1){
            //只有玩家1可以进行"开始游戏操作"
            if($userRoomUser[0]->player_num==RoomPlayer::ROLE_TYPE_MASTER){
                $room = Room::find()->where(['id'=>$userRoomUser[0]->room_id])->one();
                if($room){
                    if($room->status==Room::STATUS_PREPARING){
                        $roomUser = RoomPlayer::find()->where(['room_id'=>$room->id])->all();
                        if(count($roomUser)>2){
                            $msg = '房间中人数大于2，数据错误';
                        }else if(count($roomUser)==2){
                            foreach($roomUser as $u){
                                if($u->player_num == 2) {
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
            $roomUser = RoomPlayer::find()->where(['room_id'=>$room->id])->all();
            $roomUserCount = count($roomUser);
            if($roomUserCount==2){
                $masterFlag = false;
                $masterId = 0;
                $guestFlag = false;
                $guestId = 0;
                foreach($roomUser as $u){
                    if($u->player_num==1){
                        $masterFlag = true;
                        $masterId = $u->user_id;
                    }elseif($u->player_num==RoomPlayer::ROLE_TYPE_GUEST){
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
        $userRoomUser = RoomPlayer::find()->where(['user_id'=>$user_id])->all();
        if(count($userRoomUser) == 1){
            //TODO 暂时只有玩家1可以进行"开始游戏操作"
            if($userRoomUser[0]->player_num==RoomPlayer::ROLE_TYPE_MASTER){
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
                            $guest_user = RoomPlayer::find()->where(['room_id'=>$room->id,'player_num'=>RoomPlayer::ROLE_TYPE_GUEST])->one();
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
        $user_id = Yii::$app->user->id;
        $room_player = RoomPlayer::find()->where(['user_id'=>$user_id])->one();
        if($room_player){
            $room_id = $room_player->room_id;
            $game = Game::find()->where(['room_id'=>$room_id,'status'=>Game::STATUS_PLAYING])->one();
            if($game) {
                $success = true;
            }else{
                $msg = '你所在房间游戏未开始，错误';
            }
        }else{
            $msg = '不在房间中';
        }

        return [$success,$msg];
    }

    public static function getInfo(){
        $success = false;
        $msg = '';
        $data = [];
        $user_id = Yii::$app->user->id;
        $userRoomUser = RoomPlayer::find()->where(['user_id'=>$user_id])->all();
        if(count($userRoomUser) == 1){
            $userGame = Game::find()->where(['room_id'=>$userRoomUser[0]->room_id,'status'=>Game::STATUS_PLAYING])->all();
            if(count($userGame)==1){
                $game = $userGame[0];
                $gameCardCount = GameCard::find()->where(['game_id'=>$game->id])->count();
                if($gameCardCount==Card::CARD_NUM_ALL){
                    $data['game'] = [
                        'round_player'=>$game->round_player,
                        'round_num'=>$game->round_num
                    ];

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

        $game = Game::find()->where(['id'=>$game_id])->one();
        if($game) {
            $user_id = Yii::$app->user->id;
            //获取当前玩家角色  只获取对手手牌信息（花色和数字）  自己的手牌只获取排序信息
            $userRoomUser = RoomPlayer::find()->where(['user_id' => $user_id, 'room_id' => $game->room_id])->one();
            if (count($userRoomUser) == 1) {
                $isMaster = false;
                if($userRoomUser->player_num===RoomPlayer::ROLE_TYPE_MASTER){
                    $isMaster = true;
                }
                $masterCard = GameCard::find()->where(['game_id' => $game_id, 'type' => GameCard::TYPE_IN_PLAYER, 'player_num' => 1])->orderBy('type_ord asc')->all();
                $guestCard = GameCard::find()->where(['game_id' => $game_id, 'type' => GameCard::TYPE_IN_PLAYER, 'player_num' => 2])->orderBy('type_ord asc')->all();


                $master_hands = [];
                $guest_hands = [];

                if($isMaster){
                    foreach ($masterCard as $card) {
                        $cardArr = [
                            'ord' => $card->type_ord
                        ];
                        $master_hands[] = $cardArr;
                    }

                    foreach ($guestCard as $card) {
                        $cardArr = [
                            'color' => $card->color,
                            'num' => $card->num,
                            'ord' => $card->type_ord
                        ];
                        $guest_hands[] = $cardArr;
                    }
                }else{
                    foreach ($masterCard as $card) {
                        $cardArr = [
                            'color' => $card->color,
                            'num' => $card->num,
                            'ord' => $card->type_ord
                        ];
                        $master_hands[] = $cardArr;
                    }


                    foreach ($guestCard as $card) {
                        $cardArr = [
                            'ord' => $card->type_ord
                        ];
                        $guest_hands[] = $cardArr;
                    }
                }


                $libraryCardCount = GameCard::find()->where(['game_id' => $game_id, 'type' => GameCard::TYPE_IN_LIBRARY])->orderBy('type_ord asc')->count();
                $tableCard = GameCard::find()->where(['game_id' => $game_id, 'type' => GameCard::TYPE_ON_TABLE])->orderBy('type_ord asc')->all();
                $discardCardCount = GameCard::find()->where(['game_id' => $game_id, 'type' => GameCard::TYPE_IN_DISCARD])->orderBy('type_ord asc')->count();


                $table_cards = [];
                foreach ($tableCard as $card) {
                    //TODO 完整性检查
                    $table_cards[$card->color]++;
                }

                $data['master_hands'] = $master_hands;
                $data['guest_hands'] = $guest_hands;
                $data['library_cards_num'] = $libraryCardCount;
                $data['discard_cards_num'] = $discardCardCount;

                $data['table_cards'] = $table_cards;

                $data['cue_num'] = $game->cue_num;
                $data['chance_num'] = $game->chance_num;
            }else{
                //TODO
            }
        }else{
            //TODO
        }
        return $data;
    }


    public static function discard($ord){
        $success = false;
        $msg = '';
        $data = [];
        $user_id = Yii::$app->user->id;
        $userRoomUser = RoomPlayer::find()->where(['user_id'=>$user_id])->all();
        if(count($userRoomUser) == 1){
            $roomUser = $userRoomUser[0];
            $userGame = Game::find()->where(['room_id'=>$roomUser->room_id,'status'=>Game::STATUS_PLAYING])->all();
            if(count($userGame)==1){
                $game = $userGame[0];
                if($game->round_player==$roomUser->player_num){
                    $gameCardCount = GameCard::find()->where(['game_id'=>$game->id])->count();
                    if($gameCardCount==Card::CARD_NUM_ALL){
                        $player_num = $userRoomUser[0]->player_num;
                        //丢弃一张牌
                        GameCard::discardCard($game->id,$player_num,$ord);

                        //给这个玩家摸一张牌
                        GameCard::drawCard($game->id,$player_num);

                        //恢复一个提示数
                        self::recoverCue($game->id);

                        //交换(下一个)回合
                        self::changeRoundPlayer($game->id);

                        //插入日志 record
                        //TODO


                        $success = true;
                    }else{
                        $msg = '总卡牌数错误';
                    }
                }else{
                    $msg = '当前不是你的回合';
                }
            }else{
                $msg = '你所在房间游戏未开始/或者有多个游戏，错误';
            }
        }else{
            $msg = '你不在房间中/不止在一个房间中，错误';
        }

        return [$success,$msg,$data];
    }


    private static function recoverCue($game_id){
        $game = Game::find()->where(['id'=>$game_id])->one();
        if($game){
            if($game->chance_num < self::DEFAULT_CUE){
                $game->chance_num = $game->chance_num+1;
                if($game->save())
                    return true;
            }
        }
        return false;
    }

    private static function changeRoundPlayer($game_id){
        $game = Game::find()->where(['id'=>$game_id])->one();
        if($game){
            $game->round_player = $game->round_player==1?2:1;
            $game->round_num = $game->round_num+1;
            if($game->save())
                return true;
        }
        return false;
    }
}
