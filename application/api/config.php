<?php
//配置文件
return [
    //异常解决方案
    'exception_handle'       => '\\app\\admin\\common\\exception\\Http',
    // 是否强制使用路由
    'url_route_must'         => false,
    'book_type'              => [
<<<<<<< HEAD
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
=======
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
>>>>>>> e96a91b8b0c47c2e9be76a0bbb7c45c9a5f6bb97
    ],
    'order_state_id'         => [
        '1'     => 'test'
    ]
];
