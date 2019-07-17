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
        'shopCart/:user_id'=> ['api/user/shopCart', ['method' => 'get']],
        'bookList/:type_id'   => ['api/user/bookList', ['method' => 'get']],
        'book/:book_id'   => ['api/user/book', ['method' => 'get']],
        'bookType'   => ['api/user/bookType', ['method' => 'get']],
        'evaluateList/:book_id'   => ['api/user/evaluateList', ['method' => 'get']],
        'orderCancle'   => ['api/index/orderCancel', ['method' => 'POST']],
        'orderUpdate'   => ['api/user/orderUpdate', ['method' => 'POST']],
        'orderDelete'   => ['api/user/orderDelete', ['method' => 'POST']],
        'orderStateUpdate'   => ['api/user/orderStateUpdate', ['method' => 'POST']],
        'cartAdd'   => ['api/user/cartAdd', ['method' => 'POST']],
        'cartDelete'   => ['api/user/cartDelete', ['method' => 'POST']],
        'cartUpdate'   => ['api/user/cartUpdate', ['method' => 'POST']],
        'collectAdd'   => ['api/user/collectAdd', ['method' => 'POST']],
    ]
];
