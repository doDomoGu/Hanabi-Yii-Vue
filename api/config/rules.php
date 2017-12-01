<?php
return [
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['v1/user','v1/room'],
        'pluralize' => false
    ],

    'POST v1/auth' => 'v1/user/auth',  //提交登录 生成token
    'DELETE v1/auth' => 'v1/user/auth-delete', //退出 清空token
    //'POST v1/auth-delete' => 'v1/user/auth-delete',  //退出 清空token

    'OPTIONS v1/auth' => 'v1/user/auth-delete',
    'GET v1/auth' => 'v1/user/auth-user-info', //读取用户信息（自动登录）
];