<?php
$v = 'v1';
return [

    'POST '.$v.'/room/enter' => $v.'/room/enter',  //进入房间 （是否有位置， 如有密码，密码验证）
    'POST '.$v.'/room/is-in-room' => $v.'/room/is-in-room',  //判断是否在房间中  如是返回房间i
    'POST '.$v.'/room/exit' => $v.'/room/exit',  //判断是否在房间中  如是返回房间i


    [
        'class' => 'yii\rest\UrlRule',
        'controller' => [$v.'/user',$v.'/room'],
        'pluralize' => false
    ],

    'POST '.$v.'/auth' => $v.'/user/auth',  //提交登录 生成token
    'DELETE '.$v.'/auth' => $v.'/user/auth-delete', //退出 清空token
    //'POST v1/auth-delete' => 'v1/user/auth-delete',  //退出 清空token

    'OPTIONS '.$v.'/auth' => $v.'/user/auth-delete',
    'GET '.$v.'/auth' => $v.'/user/auth-user-info', //读取用户信息（自动登录）



];