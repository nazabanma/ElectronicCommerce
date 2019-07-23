<?php
//配置文件
return [
    'exception_handle'       => '\\app\\admin\\common\\exception\\Http',
    //无需验证的控制器 方法
    'no_auth'    => [
        ['c' => 'Index', 'a' => 'index'],
        ['c' => 'User', 'a' => 'login'],
    ],

    'book_type_to_id'              => [
        '全部' => '0',
        '计算机' => '1',
        '科学' => '2',
        '经济'  => '3',
        '军事'  => '4',
        '哲学'  => '5',
        '数学'  => '6',
        '航空'  => '7',
        '文学'  => '8',
        '艺术'  => '9',
        '语言' => '10',
    ],
];
