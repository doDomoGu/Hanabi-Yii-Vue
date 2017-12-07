<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "game_card".
 *
 * @property integer $id
 * @property integer $game_id
 * @property integer $type
 * @property integer $type_ord
 * @property integer $player_num
 * @property integer $color
 * @property integer $num
 * @property integer $ord
 * @property string $created_at
 * @property string $updated_at
 */
class GameCard extends ActiveRecord
{
    const TYPE_IN_PLAYER = 1;
    const TYPE_IN_LIBRARY = 2;
    const TYPE_ON_TABLE = 3;
    const TYPE_IN_DISCARD = 4;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'game_card';
    }

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
            [['game_id'], 'required'],
            [['game_id', 'type', 'type_ord', 'player_num', 'color', 'num', 'ord'], 'integer'],
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
            'game_id' => 'Game ID',  //对应游戏ID
            'type' => 'Type',   //牌类型 1:玩家手上,2:牌库中,3:桌面上(燃放成功),4:弃牌堆(包括燃放失败也进入弃牌堆)
            'type_ord' => 'Type Ord', //初始值 和 ord字段一样代表生成的随机花色和颜色排序（1至50），根据type不同，意义不同:1在玩家手中表示 从左至右的顺序(1-5),3设置为0，4表示弃牌堆的顺序从1开始增加 越大表示越后面丢弃
            'player_num' => 'Player Num', //玩家对应的序号，1：房主 2：2P
            'color' => 'Color', //颜色Card中colors数组 1-5
            'num' => 'Num', //数字Card中numbers数组 1-10
            'ord' => 'Ord', //初始排序 1-50
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }



    //初始化牌库
    public static function initLibrary($game_id){
        $return = false;
        $game = Game::find()->where(['id'=>$game_id,'status'=>1])->one();
        if($game){
            $cardCount = self::find()->where(['game_id'=>$game_id])->count();
            if($cardCount>0){
                $cardArr = [];
                foreach(Card::$colors as $k=>$v){
                    foreach(Card::$numbers as $k2=>$v2){
                        $cardArr[] = [$k,$k2];
                    }
                }
                shuffle($cardArr);

                $insertArr = [];
                $ord = 1;
                foreach($cardArr as $c){
                    $insertArr[] = [$game_id,self::TYPE_IN_LIBRARY,$ord,0,$c[0],$c[1],$ord];
                    $ord++;
                }

                Yii::$app->db->createCommand()->batchInsert(
                    self::tableName(),
                    ['game_id','type','type_ord','player_num','color','num','ord'],
                    $insertArr
                )->execute();

                $cards = GameCard::find()->where(['game_id'=>$game->id])->count();
                if($cards==Card::CARD_NUM_ALL){
                    $return = true;
                }else{
                    //TODO 错误处理
                }
            }else{
                //TODO 错误处理

                //echo 'game card exist';exit;
            }
        }else{
            //TODO 错误处理

            //game not exit
        }
        return $return;
    }

    //摸一张牌
    public static function drawCard($game_id,$player_num){
        $return = false;
        //统计牌的总数 应该为50张
        $count = self::find()->where(['game_id'=>$game_id])->count();
        if($count==Card::CARD_NUM_ALL){
            //选取牌库上的第一张牌
            $card = self::find()->where(['game_id'=>$game_id,'type'=>self::TYPE_IN_LIBRARY])->orderBy('type_ord asc')->one();
            if($card){
                //查找玩家手上排序最大的牌，确定新模的牌的序号 ord
                $playerCard = self::find()->where(['game_id'=>$game_id,'type'=>self::TYPE_IN_PLAYER,'player_num'=>$player_num])->orderBy('type_ord desc')->one();
                if($playerCard){
                    $ord = $playerCard->ord+1;
                }else{
                    $ord = 1;
                }
                $card->type = self::TYPE_IN_PLAYER;
                $card->player_num = $player_num;
                $card->ord = $ord;
                if($card->save()){
                    $return = true;
                }
            }else{
                echo 'no card to draw';
            }
        }else{
            echo 'game card num wrong';
        }
        return $return;
    }

    //交换手牌顺序
    public static function changePlayerCardOrd($game_id,$player,$cardId1,$cardId2){
        $card1 = self::find()->where(['game_id'=>$game_id,'type'=>self::TYPE_IN_PLAYER,'player'=>$player,'id'=>$cardId1,'status'=>1])->one();
        $card2 = self::find()->where(['game_id'=>$game_id,'type'=>self::TYPE_IN_PLAYER,'player'=>$player,'id'=>$cardId2,'status'=>1])->one();
        if($card1 && $card2){
            $card1->ord = $card2->ord;
            $card2->ord = $card1->ord;
            $card1->save();
            $card2->save();
        }else{
            echo 'card info wrong';
        }
    }


    //获取牌库/手牌 等信息
    public static function getCardInfo($game_id){
        $cardInfo = [
            'player_1'=>[],
            'player_2'=>[],
            'library'=>[],
            'table'=>[],
            'discard'=>[],
        ];
        $gameCard = self::find()->where(['game_id'=>$game_id,'status'=>1])->orderBy('ord asc')->all();
        if(count($gameCard)==50){
            foreach($gameCard as $gc){
                $temp = ['id'=>$gc->id,'color'=>$gc->color,'num'=>$gc->num];
                if($gc->type==self::TYPE_IN_PLAYER){
                    if($gc->player==1){
                        $cardInfo['player_1'][]=$temp;
                    }elseif($gc->player==2){
                        $cardInfo['player_2'][]=$temp;
                    }
                }elseif($gc->type==self::TYPE_IN_LIBRARY){
                    $cardInfo['library'][]=$temp;
                }elseif($gc->type==self::TYPE_ON_TABLE){
                    $cardInfo['table'][]=$temp;
                }elseif($gc->type==self::TYPE_IN_DISCARD){
                    $cardInfo['discard'][]=$temp;
                }
            }
        }
        return $cardInfo;
    }

    //获取当前应插入弃牌堆的ord数值，即当前弃牌堆最小排序的数值减1，没有则为49
    public static function getInsertDiscardOrd($game_id){
        $lastDiscardCard = GameCard::find()->where(['game_id'=>$game_id,'type'=>GameCard::TYPE_IN_DISCARD,'status'=>1])->orderBy('ord asc')->one();
        if($lastDiscardCard){
            $ord = $lastDiscardCard->ord - 1;
        }else{
            $ord = 49;
        }
        return $ord;
    }

    //整理手牌排序 （当弃牌或者打出手牌后，进行操作）
    public static function sortCardOrdInPlayer($game_id,$player){
        $cards = GameCard::find()->where(['game_id'=>$game_id,'type'=>GameCard::TYPE_IN_PLAYER,'player'=>$player,'status'=>1])->orderBy('ord asc')->all();
        $i=0;
        foreach($cards as $c){
            $c->ord = $i;
            $c->save();
            $i++;
        }
    }

    //获取桌面上成功燃放的烟花 卡牌
    public static function getCardsTopOnTable($game_id){
        $cardsOnTable = [
            [0,0,0,0,0],
            [0,0,0,0,0],
            [0,0,0,0,0],
            [0,0,0,0,0],
            [0,0,0,0,0]
        ];
        $cards = GameCard::find()->where(['game_id'=>$game_id,'type'=>GameCard::TYPE_ON_TABLE,'status'=>1])->all();

        foreach($cards as $c){
            $k1=$c->color;
            $k2=Card::$numbers[$c->num] - 1;
            $cardsOnTable[$k1][$k2] = 1;
        }

        $verify = true;//验证卡牌 ，按数字顺序
        $cardsTop = [0,0,0,0,0]; //每种颜色的最大数值
        foreach($cardsOnTable as $k1 => $row){
            $count = 0;
            $top = 0;
            foreach($row as $k2=>$r){
                if($r==1){
                    $count++;
                    $top = $k2+1;
                }
            }
            if($count==$top){
                $cardsTop[$k1] = $top;
            }else{
                $verify=false;
            }
        }

        if($verify){
            return $cardsTop;
        }else{
            return false;
        }

    }
}
