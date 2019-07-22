<?php
//配置文件
return [
    //异常解决方案
    'exception_handle'       => '\\app\\admin\\common\\exception\\Http',
    // 是否强制使用路由
    'url_route_must'         => false,
    'book_type'              => [
        '1'    => '好看类型',
        '2'    => '非常好看类型',
        '66'   => '异常好看类型',
    ],
    'order_state_id'         => [
        '1'     => 'test'
    ]
];
