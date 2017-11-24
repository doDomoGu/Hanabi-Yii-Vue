<?php

namespace app\modules\v1\controllers;

use Yii;
use app\models\User;
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
            'optional' => [
                'index',
                'create',
                //'signup-test',
                //'view',
                'login'
            ],
        ];
        return $behaviors;
    }



    public function actionLogin()
    {
        $username = Yii::$app->request->post('username');
        $password = Yii::$app->request->post('password');

        var_dump($username);
        var_dump($password);exit;

        return $this->render('index');
    }

}
