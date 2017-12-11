<?php

namespace app\modules\v1\controllers;

use app\models\Room;
use app\models\RoomUser;
use Yii;

class MyRoomController extends MyActiveController
{
    public function init(){
        $this->modelClass = Room::className();
        parent::init();
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        return $behaviors;
    }


    public function actionEnter(){
        $return = $this->return;

        $room_id = Yii::$app->request->post('room_id');

        list($return['success'],$return['msg']) = Room::enter($room_id,Yii::$app->user->id);

        if($return['success']){
            $return['data'] = ['room_id'=>$room_id];
        }

        return $return;
    }

    public function actionExit(){
        $return = $this->return;

        list($return['success'],$return['msg']) = Room::exitRoom(Yii::$app->user->id);

        return $return;
    }


    public function actionIsInRoom(){
        $return = $this->return;


        list($return['success'],$return['msg'],$room_id) = Room::isInRoom(Yii::$app->user->id);

        if($return['success']){
            $return['data'] = ['room_id'=>$room_id];
        }

        return $return;
    }

    public function actionGetInfo(){
        $return = $this->return;

        $room_id = Yii::$app->request->post('room_id');

        list($return['success'],$return['msg'],$return['data']) = Room::getInfo($room_id);

        return $return;
    }

    public function actionDoReady(){
        $return = $this->return;

        $room_id = Yii::$app->request->post('room_id');

        list($return['success'],$return['msg']) = Room::doReady($room_id);

        return $return;
    }


    public function actionStartGame(){
        $return = $this->return;

        $room_id = Yii::$app->request->post('room_id');

        list($return['success'],$return['msg']) = Room::startGame($room_id);

        return $return;
    }
}
