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
        'api.wyxs'      => 'api',
        'admin.wyxs'    => 'admin',
        '*'             => 'index',
    ],
    '[admin]' =>
    [
        'login'                         => ['admin/user/login',            ['method' => 'POST']],
        'logOut'                        => ['admin/user/logOut',           ['method' => 'GET']],
        'userList/:page'                => ['admin/user/userList',         ['method' => 'GET']],
        'findUser/:user_id'             => ['admin/user/findUser',         ['method' => 'GET']],
        'findUserFuzzy'                 => ['admin/user/findUserFuzzy',    ['method' => 'POST']],
        'delUser'                       => ['admin/user/delUser',          ['method' => 'POST']],



        'upload'                        => ['admin/admin/upload',    ['method' => 'POST']],


        'bookList/:page'                => ['admin/book/bookList',      ['method' => 'GET']],
        'findBookFuzzy'                 => ['admin/book/findBookFuzzy', ['method' => 'POST']],
        'bookDetail/:book_id'           => ['admin/book/bookDetail',    ['method' => 'GET']],
        'delBook'                       => ['admin/book/delBook',       ['method' => 'POST']],
        'updateBook'                    => ['admin/book/updateBook',    ['method' => 'POST']],
        'addBook'                       => ['admin/book/addBook',       ['method' => 'POST']],


        'orderList/:page'               => ['admin/order/orderList',            ['method' => 'GET']],
        'findOrderFuzzy'                => ['admin/order/findOrderFuzzy',       ['method' => 'POST']],
        'getOrderItem/:order_id'        => ['admin/order/getOrderItem',         ['method' => 'GET']],
        'updateOrderState'              => ['admin/order/updateOrderState',     ['method' => 'POST']],


    ],
    '[user]' =>
    [
        'userInfo/:user_id'          => ['api/user/userInfo',           ['method' => 'GET']],
        'userAdd'                    => ['api/user/userAdd',            ['method' => 'POST']],
        'userFind'                   => ['api/user/userFind',           ['method' => 'POST']],
        'headImg'                    => ['api/user/headImg',            ['method' => 'POST']],
    ],

    '[address]' =>
    [
        'address/:user_id'          => ['api/address/myAddress',       ['method' => 'GET']],
        'editAddress'               => ['api/address/editAddress',     ['method' => 'POST']],
        'addAddress'                => ['api/address/addAddress',      ['method' => 'POST']],
        'deleteAddress'             => ['api/address/deleteAddress',   ['method' => 'POST']],
        'findAddress'               => ['api/address/findAddress',     ['method' => 'POST']],
        
    ],

    '[collect]' =>
    [
        'collect'                   => ['api/collect/myCollect',        ['method' => 'POST']],
        'collectAdd'                => ['api/collect/collectAdd',       ['method' => 'POST']],
        'collectDelete'             => ['api/collect/collectDelete',    ['method' => 'POST']],
        'collectDeleteAll'          => ['api/collect/collectDeleteAll', ['method' => 'POST']],
        'removeToCart'              => ['api/collect/removeToCart',     ['method' => 'POST']],
    ],

    '[evaluate]' =>
    [
        'evaluate'                  => ['api/evaluate/evaluate',             ['method' => 'POST']],
        'evaluateLike'              => ['api/evaluate/evaluateLike',         ['method' => 'POST']],
        'evaluateList'              => ['api/evaluate/evaluateList',         ['method' => 'POST']],
        'evaluateDelete'            => ['api/evaluate/evaluateDelete',       ['method' => 'POST']],
        'evaluateDeleteAll'         => ['api/evaluate/evaluateDeleteAll',    ['method' => 'POST']],
        'evaluateOrder'             => ['api/evaluate/evaluateOrder',        ['method' => 'POST']],
        'upLoadImg'                 => ['api/evaluate/upLoadImg',            ['method' => 'POST']],
    ],

    '[book]'   =>
    [
        'bookList/:type_id'         => ['api/book/bookList',        ['method' => 'GET']],
        'bookDetail'                => ['api/book/bookDetail',      ['method' => 'POST']],
        'bookType'                  => ['api/book/bookType',        ['method' => 'GET']],
        'evaluateList/:book_id'     => ['api/book/evaluateList',    ['method' => 'GET']],
        'bookFind'            => ['api/book/bookFind',              ['method' => 'POST']],
        'bookAdd'                   => ['api/book/bookAdd',         ['method' => 'POST']],
        'bookEdit'                  => ['api/book/bookEdit',        ['method' => 'POST']],
        'bookDelete'                => ['api/book/bookDelete',      ['method' => 'POST']],
        'bookListByPage'            => ['api/book/bookListByPage',  ['method' => 'POST']],



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
        'allOrder'                  => ['api/order/allOrder',           ['method' => 'POST']],
        'orderCreate'               => ['api/order/orderCreate',        ['method' => 'POST']],
        'orderCancel'               => ['api/order/orderCancel',        ['method' => 'POST']],
        'orderUpdate'               => ['api/order/orderUpdate',        ['method' => 'POST']],
        'orderDelete'               => ['api/order/orderDelete',        ['method' => 'POST']],
        'orderStateUpdate'          => ['api/order/orderStateUpdate',   ['method' => 'POST']],
        'order'                     => ['api/order/myOrder',            ['method' => 'POST']],
    ]

];
