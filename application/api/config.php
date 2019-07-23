<?php
//配置文件
return [
    //异常解决方案
    'exception_handle'       => '\\app\\admin\\common\\exception\\Http',
    // 是否强制使用路由
    'url_route_must'         => false,
    'book_type'              => [
        ['id' => '0', 'name'  => '全部',],
        ['id' => '1', 'name'  => '计算机',],
        ['id' => '2', 'name'  => '科学',],
        ['id' => '3', 'name'  => '经济',],
        ['id' => '4', 'name'  => '军事',],
        ['id' => '5', 'name'  => '哲学',],
        ['id' => '6', 'name'  => '数学',],
        ['id' => '7', 'name'  => '航空',],
        ['id' => '8', 'name'  => '文学',],
        ['id' => '9', 'name'  => '艺术',],
        ['id' => '10', 'name'  => '语言',],
    ],
    'order_state_id'         => [
        '1'     => 'test'
    ]
];
