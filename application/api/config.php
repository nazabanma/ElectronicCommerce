<?php
//配置文件
return [
    //异常解决方案
    'exception_handle'       => '\\app\\admin\\common\\exception\\Http',
    // 是否强制使用路由
    'url_route_must'         => false,
    'book_type'              => [
        '1'     => '计算机',
        '2'     => '科学',
        '3'     => '经济',
        '4'     => '军事',
        '5'     => '哲学',
        '6'     => '数学',
        '7'     => '航空',
        '8'     => '文学',
        '9'     => '艺术',
        '10'    => '语言',
    ],
    'order_state_id'         => [
        '1'     => 'test'
    ]
];
