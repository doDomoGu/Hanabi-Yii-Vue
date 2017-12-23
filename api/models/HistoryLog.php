<?php

namespace app\models;

use Symfony\Component\BrowserKit\History;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
/**
 * This is the model class for table "history_log".
 *
 * @property integer $id
 * @property integer $history_id
 * @property string $content_param
 * @property string $content
 * @property string $created_at
 */
class HistoryLog extends \yii\db\ActiveRecord
{
    const TYPE_PLAY_CARD = 1;
    const TYPE_DISCARD_CARD  = 2;
    const TYPE_CUE_CARD = 3;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'history_log';
    }


    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at']
                ],
                'value' => new Expression('NOW()'),  //时间戳（数字型）转为 日期字符串
            ]
        ];
    }
    
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['history_id','type'], 'required'],
            [['history_id','type'], 'integer'],
            [['content_param', 'content'], 'string'],
            [['created_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'history_id' => 'History ID',
            'content_param' => 'Content Param',
            'content' => 'Content',
            'created_at' => 'Created At',
        ];
    }


    public static function getContentByDiscard($room_id,$card_ord){
        $success = false;
        $content_param = '';
        $content = '';
        $game = Game::find()->where(['room_id'=>$room_id,'status'=>Game::STATUS_PLAYING])->one();
        if($game){
            $round_num = $game->round_num;
            //$round_player_is_host =  $game->
            /*$host_player = RoomPlayer::find()->where(['room_id'=>$room_id,'is_host'=>1])->one();
            $guest_player = RoomPlayer::find()->where(['room_id'=>$room_id,'is_host'=>0])->one();
            if($host_player && $guest_player){
                if()
            }*/
            $card = GameCard::find()->where(['room_id'=>$room_id,'ord'=>$card_ord])->one();
            if($card){

                $player = User::find()->where(['id'=>Yii::$app->user->id])->one();

                $replace_param = [
                    'round_num'=>$round_num,
                    'card_color'=>$card->color,
                    'card_num'=>$card->num,
                    'player_name'=>$player->nickname
                ];

                $template = '回合[round_num]:[player_name]丢弃了[card_color]-[card_num]';

                $param = array_merge(
                    $replace_param,
                    [
                        'player_id'=>Yii::$app->user->id,
                        'template'=>$template
                    ]
                );

                $content_param = json_encode($param);

                $content = self::replaceContent($replace_param,$template);

                $success = true;
            }
        }

        return [$success,$content_param,$content];
    }


    private static function replaceContent($param,$template){
        $content = $template;
        $search = [];
        $replace = [];
        foreach($param as $k=>$v){
            if(in_array($k,['template'])){
                continue;
            }

            $search[] = '['.$k.']';

            if($k=='card_color'){
                $replace[] = Card::$colors[$v];
            }else if($k=='card_num'){
                $replace[] = Card::$numbers[$v];
            }else{
                $replace[] = $v;
            }
        }

        if(!empty($search)) {
            $content = str_replace($search, $replace, $template);
        }

        return $content;
    }



    public static function getList($room_id) {
        $success = false;
        $msg = '';
        $data = [];
        $game = Game::find()->where(['room_id' => $room_id, 'status' => Game::STATUS_PLAYING])->one();
        if ($game) {
            $history = History::find()->where(['room_id' => $room_id, 'status' => History::STATUS_PLAYING])->one();
            if ($history) {
                $logs = HistoryLog::find()->where(['history_id' => $history->id])->orderBy('created_at asc')->all();
                foreach ($logs as $log) {
                    $data[] = $log->content;
                }
                $success = true;
            } else {
                $msg = 'history不存在';
            }
        } else {
            $msg = '游戏不存在';
        }
        return [$success,$msg,$data];
    }
}
