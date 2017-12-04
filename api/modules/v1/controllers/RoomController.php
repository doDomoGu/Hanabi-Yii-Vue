<?php

namespace app\modules\v1\controllers;

use app\models\Room;
use app\models\RoomUser;
use Yii;

class RoomController extends MyActiveController
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
        //$return['success'] = $success;
        //$return['msg'] = $msg;
        return $return;
    }

}
