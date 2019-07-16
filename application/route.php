<?php

use think\Route;

// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------




return [
    '__pattern__' => [
        'name' => '\w+',
    ],

    '__domain__' => [
        'api'      => 'api',
        '*'         => 'index',
    ],
    '[user]' =>
    [
        'collect'       => ['api/user/myCollect', ['method' => 'get']],
        'order'         => ['api/user/myOrder', ['method' => 'get']],
        'address'       => ['api/user/myAddress', ['method' => 'get']],
        'editAddress'   => ['api/user/editAddress', ['method' => 'POST']],
    ]
];
