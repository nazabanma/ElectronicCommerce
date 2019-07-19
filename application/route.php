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
        
    ],

    '[address]' =>
    [
        'address/:user_id'          => ['api/address/myAddress',       ['method' => 'GET']],
        'editAddress'               => ['api/address/editAddress',     ['method' => 'POST']],
    ],

    '[collect]' =>
    [
        'collect/:user_id'          => ['api/collect/myCollect',        ['method' => 'GET']],
        'collectAdd'                => ['api/collect/collectAdd',       ['method' => 'POST']],
        'collectDelete'             => ['api/collect/collectDelete',    ['method' => 'POST']],
        'collectDeleteAll'          => ['api/collect/collectDeleteAll', ['method' => 'POST']],
        'removeToCart'              => ['api/collect/removeToCart',     ['method' => 'POST']],
    ],

    '[evaluate]' =>
    [
        'evaluate'                  => ['api/evaluate/evaluate',        ['method' => 'POST']],
        'evaluateLike'              => ['api/evaluate/evaluateLike',    ['method' => 'POST']],
        'evaluateList/:book_id'              => ['api/evaluate/evaluateList',    ['method' => 'GET']],
    ],

    '[book]'   =>
    [
        'bookList/:type_id'         => ['api/book/bookList',        ['method' => 'GET']],
        'bookDetail/:book_id'       => ['api/book/bookDetail',      ['method' => 'GET']],
        'bookType'                  => ['api/book/bookType',        ['method' => 'GET']],
        'evaluateList/:book_id'     => ['api/book/evaluateList',    ['method' => 'GET']],
        'bookFind/:book_id'         => ['api/book/bookFind',        ['method' => 'GET']],
        'bookAdd'                   => ['api/book/bookAdd',         ['method' => 'POST']],
        'bookEdit'                  => ['api/book/bookEdit',        ['method' => 'POST']],
        'bookDelete'                => ['api/book/bookDelete',      ['method' => 'POST']],
        
        
    ],

    '[cart]'   =>
    [
        'shopCart/:user_id'         => ['api/cart/shopCart',        ['method' => 'GET']],
        'cartAdd'                   => ['api/cart/cartAdd',         ['method' => 'POST']],
        'cartDelete'                => ['api/cart/cartDelete',      ['method' => 'POST']],
        'cartDeleteAll'             => ['api/cart/cartDeleteAll',   ['method' => 'POST']],
        'cartUpdate'                => ['api/cart/cartUpdate',      ['method' => 'POST']],
        'removeToCollect'           => ['api/cart/removeToCollect', ['method' => 'POST']],
    ],
    '[order]'   =>
    [
        'order'                     => ['api/order/allOrder',           ['method' => 'POST']],
        'orderCreate'               => ['api/order/orderCreate',        ['method' => 'POST']],
        'orderCancel'               => ['api/order/orderCancel',        ['method' => 'POST']],
        'orderUpdate'               => ['api/order/orderUpdate',        ['method' => 'POST']],
        'orderDelete'               => ['api/order/orderDelete',        ['method' => 'POST']],
        'orderStateUpdate'          => ['api/order/orderStateUpdate',   ['method' => 'POST']],
        'order/:user_id'            => ['api/order/myOrder',            ['method' => 'GET']],
        
    ]

];
