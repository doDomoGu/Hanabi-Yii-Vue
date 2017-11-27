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



    public function actionLogin()
    {
        /*$pwd1 = H_JWT::generatePassword('1231233');
        $pwd2 = H_JWT::generatePassword('123123');

        var_dump($pwd1);

        echo '<br/>'.$pwd1;
$pwd1 = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJwYXNzd29yZCI6IjEyMzEyMzMifQ.ljI32JGTU5o2fWPPvhKqb2QraXN2_geUqiSBD0qdDAM';
        echo '<br/>'.H_JWT::parse($pwd1);



        echo '<br/>';



        var_dump($pwd2);
        echo '<br/>'.$pwd2;

        exit;*/


        $token = H_JWT::generateToken(1);
        echo $token;
        var_dump((string)$token);exit;
        $username = Yii::$app->request->post('username');
        $password = Yii::$app->request->post('password');

        var_dump($username);
        var_dump($password);exit;

        return $this->render('index');
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


        /*var that = this;

        //查用户名
        mysql.query('SELECT * FROM `' + that.modelName + '` where username = ?',data.username, function (error, res) {
            if (error){
                return callback(error);
            }
            var result = {
                error_msg : '未知错误',
            success : false,
            token : '',
            user_id : 0,
            user_info: {},
            roles : []
        };
        if(res.length == 1){
            var _res = res[0];

            if(res[0].password==data.password){



                result.success = true;
                result.error_msg = false;
                result.user_id = _res.id;
                result.user_info = _res;


                mysql.query('select ug.alias from `usergroup_user` ugu join `usergroup` ug on ugu.usergroup_id = ug.id where ugu.user_id = ? ',[_res.id], function (error, res) {
                    if (error) throw error;

                    var roles = [];
                    if(res.length>0) {

                        for (var i in res) {
                            roles.push(res[i].alias);
                        }
                    }

                    result.roles = roles;


                    var token = generateToken(_res.id);
                    result.token = token;
                    var expired = new Date(new Date().getTime() + 60*60*24*1000);

                    var expired_time = '';
                    expired_time += expired.getFullYear();
                    expired_time += "-"+ (expired.getMonth()+1 < 10 ? '0' + (expired.getMonth()+1) : (expired.getMonth()+1));
                    expired_time += "-"+ (expired.getDate() < 10 ? '0' + expired.getDate() : expired.getDate());
                    expired_time += " "+ (expired.getHours() < 10 ? '0' + expired.getHours() : expired.getHours());
                    expired_time += ":"+ (expired.getMinutes() < 10 ? '0' + expired.getMinutes() : expired.getMinutes());
                    expired_time += ":"+ (expired.getSeconds() < 10 ? '0' + expired.getSeconds() : expired.getSeconds());


                    mysql.query('INSERT INTO `user_auth_token` SET ?',{user_id:_res.id,token:token,expired_time:expired_time}, function (error, res) {
                        if (error) throw error;


                        return callback(null,result);
                        //return callback(null,{id:res.insertId});
                    });
                    //return callback(null,result);

                });

                //result.roles = auth_roles[_res.id]!=undefined?auth_roles[_res.id]:[];








            }else{
                result.error_msg = '用户名或密码错误 (001)';
                //return callback(null,{error:'don\'t find the user'});
                return callback(null,result);
            }

        }else{
            //return callback(null,{error:'don\'t find the user'});
            result.error_msg = '用户名或密码错误 (002)';
            return callback(null,result);
        }

    });*/
    }




}
