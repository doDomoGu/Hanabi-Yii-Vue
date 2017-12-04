<?php

namespace app\modules\v1\controllers;

use app\models\Room;
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
        $room = Room::find()->where(['id'=>$room_id])->one();
        if($room){
            if($room->password!=''){
                $return['msg'] = '房间被锁住了';
            }else{
                $return['success'] = true;
                $return['msg'] = '房间进入成功';
            }
        }else{
            $return['msg'] = '房间号错误';
        }

        return $return;
    }

}
