<?php

namespace app\models;

use yii\db\ActiveRecord;


class Card extends ActiveRecord
{
    const CARD_NUM_ALL = 50;

    public static $colors = ['白','蓝','黄','红','绿'];

    public static $numbers = [1,1,1,2,2,3,3,4,4,5];

}