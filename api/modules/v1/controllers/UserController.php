<?php

namespace app\modules\v1\controllers;


use app\models\UserAuth;
use Yii;

use app\components\H_JWT;
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
                //'view',
                'create',
                //'signup-test',
                //'view',
                'auth'
            ],
        ];
        return $behaviors;
    }

    //重写checkAccess 控制权限
    /*public function checkAccess($action, $model = null, $params = [])
    {

        throw new \yii\web\ForbiddenHttpException(sprintf('You can only %s articles that you\'ve created.', $action));

    }*/

    public function actionAuth(){
        $return = [
            'result' => false,
            'errormsg' => ''
        ];
        $username = Yii::$app->request->post('username');
        $password = Yii::$app->request->post('password');
        if($username!='' && $password!=''){
            $user = User::findByUsername($username);
            if($user){
                if($user->password == md5($password)){
                    $return['result'] = true;
                    $token = H_JWT::generateToken($user->id);
                    $auth = new UserAuth();
                    $auth->user_id = $user->id;
                    $auth->token = $token;
                    $auth->expired_time = date('Y-m-d H:i:s',strtotime('+1 day'));
                    $auth->save();

                    $return['token'] = $token;

                }else{
                    $return['errormsg'] = '密码错误';
                }

            }else{
                $return['errormsg'] = '用户名错误';
            }
        }else{
            $return['errormsg'] = '提交数据错误';
        }
        return $return;
    }

    public function actionAuthDelete(){
        $return = [
            'result' => false,
            'errormsg' => ''
        ];
        $token = Yii::$app->request->get('access-token');

        $auth = UserAuth::find()->where(['token'=>$token])->one();

        if($auth){
            $auth->expired_time = date('Y-m-d H:i:s',strtotime('-1 second'));
            if($auth->save()){
                $return['result'] = true;
            }else{
                $return['errormsg'] = 'Token数据错误(001)';
            }

        }else{
            $return['errormsg'] = 'Token数据错误(002)';
        }
        return $return;
    }

    public function actionAuthUserInfo(){
        $return = [
            'result' => false,
            'errormsg' => ''
        ];
        $token = Yii::$app->request->get('access-token');

        $auth = UserAuth::find()->where(['token'=>$token])->one();

        if($auth){
            $user = User::find()->where(['id'=>$auth->user_id])->one();
            if($user)
                $return = $user->attributes;
            else{
                $return['errormsg'] = 'User数据错误';
            }
        }else{
            $return['errormsg'] = 'Auth数据错误';
        }
        return $return;
    }




}
