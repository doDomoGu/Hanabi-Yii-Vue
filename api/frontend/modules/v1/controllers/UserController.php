<?php

namespace frontend\modules\v1\controllers;

use common\models\User;
use yii\rest\ActiveController;
use yii\web\Response;
use yii\filters\auth\QueryParamAuth;

class UserController extends ActiveController
{
    public function init(){
        $this->modelClass = User::className();
        parent::init();
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        //$behaviors['contentNegotiator']['formats'] = ['application/json' => Response::FORMAT_JSON];
        $behaviors['authenticator'] = [
            'class' => QueryParamAuth::className(),
            // 设置token名称，默认是access-token
            //'tokenParam' => 'access_token',
            /*'optional' => [
                'index',
                'signup-test',
                'view'
            ],*/
        ];
        return $behaviors;
    }



    public function actionIndex()
    {
        return $this->render('index');
    }


}
