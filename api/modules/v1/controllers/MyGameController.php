<?php

namespace app\modules\v1\controllers;

use Yii;
use app\models\Game;

class MyGameController extends MyActiveController
{
    public function init(){
        $this->modelClass = Game::className();
        parent::init();
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        return $behaviors;
    }


    public function actionStart(){
        $return = $this->return;

        list($return['success'],$return['msg'],$game_id) = Game::start();

        if($return['success']){
            $return['data'] = ['game_id'=>$game_id];
        }

        return $return;
    }

    public function actionGetInfo(){
        $return = $this->return;

        list($return['success'],$return['msg'],$return['data']) = Game::getInfo();

        return $return;
    }


    public function actionIsInGame(){
        $return = $this->return;

        list($return['success'],$return['msg'],$return['data']) = Game::isInGame();

        return $return;
    }

    public function actionEnd(){
        $return = $this->return;

        list($return['success'],$return['msg']) = Game::end();

        return $return;
    }

    public function actionDoDiscard(){
        $return = $this->return;

        $ord = Yii::$app->request->post('cardSelectOrd');

        list($return['success'],$return['msg']) = Game::discard($ord);

        return $return;
    }
}
