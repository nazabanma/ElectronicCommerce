<?php
//配置文件
return [
    'exception_handle'       => '\\app\\admin\\common\\exception\\Http',
    //无需验证的控制器 方法
    'no_auth'    => [
        ['c' => 'Index', 'a' => 'index'],
        ['c' => 'Index', 'a' => 'login'],
    ]
];
