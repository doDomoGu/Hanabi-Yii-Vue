<?php
return [
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['v1/user'/*,'v1/auth'*/],
        'pluralize' => false
    ],
    'POST v1/auth' => 'v1/user/auth',
    'site/error' => 'site/error',
];