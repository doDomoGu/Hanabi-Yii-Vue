<?php
return [
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['v1/user'/*,'v1/auth'*/],
        'pluralize' => false
    ],
    'POST v1/auth' => 'v1/user/auth',
    'DELETE v1/auth' => 'v1/user/auth-delete',
    'GET v1/auth' => 'v1/user/auth-user-info',
];