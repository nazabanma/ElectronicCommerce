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
        '*'      => 'api',
    ],
    '[user]' =>
    [
        'collect'                   => ['api/user/myCollect',       ['method' => 'GET']],
        'order'                     => ['api/user/myOrder',         ['method' => 'GET']],
        'address'                   => ['api/user/myAddress',       ['method' => 'GET']],
        'editAddress'               => ['api/user/editAddress',     ['method' => 'POST']],
        'shopCart/:user_id'         => ['api/user/shopCart',        ['method' => 'GET']],
        'bookList/:type_id'         => ['api/user/bookList',        ['method' => 'GET']],
        'bookDetail/:book_id'       => ['api/user/bookDetail',      ['method' => 'GET']],
        'bookType'                  => ['api/user/bookType',        ['method' => 'GET']],
        'evaluateList/:book_id'     => ['api/user/evaluateList',    ['method' => 'GET']],
        'orderCreate'               => ['api/user/orderCreate',     ['method' => 'POST']],
        'orderCancel'               => ['api/user/orderCancel',     ['method' => 'POST']],
        'orderUpdate'               => ['api/user/orderUpdate',     ['method' => 'POST']],
        'orderDelete'               => ['api/user/orderDelete',     ['method' => 'POST']],
        'orderStateUpdate'          => ['api/user/orderStateUpdate',['method' => 'POST']],
        'cartAdd'                   => ['api/user/cartAdd',         ['method' => 'POST']],
        'cartDelete'                => ['api/user/cartDelete',      ['method' => 'POST']],
        'cartDeleteAll'             => ['api/user/cartDeleteAll',   ['method' => 'POST']],
        'cartUpdate'                => ['api/user/cartUpdate',      ['method' => 'POST']],
        'collectAdd'                => ['api/user/collectAdd',      ['method' => 'POST']],
        'collectDelete'             => ['api/user/collectDelete',   ['method' => 'POST']],
        'collectDeleteAll'          => ['api/user/collectDeleteAll',['method' => 'POST']],
    ]
];
