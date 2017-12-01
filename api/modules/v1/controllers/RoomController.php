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

}
