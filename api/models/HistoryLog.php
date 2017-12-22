<?php

namespace app\models;

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
                $param = [
                    'round_num'=>$round_num,
                    'card_color'=>$card->color,
                    'card_num'=>$card->num,
                ];

                $template = '回合[round_num]:[player_name]丢弃了[card_color]-[card_num]';

                $content = str_replace(array_keys($param),array_values($param),$template);

                $param['template'] = $template;
                $content_param = json_encode($param);
                $success = true;
            }
        }

        return [$success,$content_param,$content];
    }


    /*private static function replaceContent($params,$template){

    }*/
}
