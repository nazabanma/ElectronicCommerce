<?php
//配置文件
return [
    //无需验证的控制器 方法
    'no_auth'    => [
        ['c' => 'Index', 'a' => 'index'],
        ['c' => 'Index', 'a' => 'login'],
    ]
];
