<?php

namespace app\api\controller;

use app\api\model\User;
use think\Controller;
use app\api\model\ShopCart;
use app\api\model\ViewMyAddress;

class Index extends Controller
{
    public function index()
    {
        return json([
            2, 3
        ]);
    }
    public function test()
    {
        $model = new User();
        $model->data([
            'uid' => '2',
            'nick_name' => 'nikko',
            'gender' => '1',
            'province' => '福建',
            'city' => '厦门',
            'country' => 'China',
            'login_time' => time(),
        ]);

        $model->save();
    }

    public function test2()
    {
        $model = ShopCart::hasWhere('user', ['shopcart.user_id' => '2'])->select();

        return json($model);
    }

    public function test3()
    {
        $model = User::get(1);
        return json($model->shopCart);
    }

    public function test4()
    {
        $model = new ViewMyAddress();
        return json($model->where('user_id', '1')->select());
    }

    /**
     * 用户取消订单
     *
     * @param Request $request
     * @return void
     */
    public function orderCancel(Request $request)
    {
        $order_id = $request->param()['order_id'];  //订单id

        $order = new Order();
        $order = $order->where('order_id', $order_id)->find();

        if (empty($order))

            return json([
                'code'  => '401',
                'msg'   => '订单不存在'
            ]);

        $order->order_state_id = 5; //订单状态id改成取消订单id

        $result = $order->save();


        if (!$result) {
            return json([
                'code'  => 500,
                'msg'   => 'update failed'
            ]);
        }

        $orderItem = new OrderItem();
        $orderList = $orderItem->where('order_id', $order_id)->select();

        foreach ($orderList as $item) {
            $book = new Book();
            $book = $book->where('book_id', $item['book_id'])->find();

            $count = $book->book_count + $item['count'];    //加回订单的数量

            $book->book_count = $count;     //更改库存数量

            $result = $book->save();

            if (!$result) {
                return json([
                    'code'  => 500,
                    'msg'   => 'update failed'
                ]);
            }
        }

        return json([
            'code'  => 200,
            'msg'   => '取消成功'
        ]);
    }
}
