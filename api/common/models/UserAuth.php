<?php

namespace common\models;

use Yii;

/**
 * UserAuth model
 *
 * @property integer $id
 * @property string $user_id
 * @property string $token
 * @property string $expired_time
 */

class UserAuth extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_auth';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['user_id', 'integer'],
            [['token','expired_time'], 'safe'],
        ];
    }

}
