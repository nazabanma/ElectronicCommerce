<?php

namespace app\api\model;

use think\Request;
use think\Model;
use think\Config;
use think\Db;
use think\Exception;

class Order extends Model
{

    public function orderItem()
    {
        return $this->hasMany('OrderItem', 'order_id');
    }

    /**
     * 根据请求创建订单(待支付)
     *
     * @param String $request
     * @return 购买信息
     */
    public function createOrder(Request $request)
    {
        $orderList = $request->param()['order_list'];           //接收到的订单数组
        $user_id = $request->param()['user_id'];                //用户id
        $address_id = $request->param()['address_id'];          //地址id
        $remark = $request->param()['remark'];                  //订单备注


        try {
            Db::startTrans();
            //创建订单，此时订单状态为待支付
            $order = new Order([
                'user_id'           => $user_id,
                'address_id'        => $address_id,
                'order_state_id'    => 0,
                'create_time'       => date("Y-m-d H:i:s"),
                //用户是否删除订单的标志，0表示用户在查看订单列表时不删除订单
                'flag'              => 0,
                'remark'            => $remark,
            ]);
            $result = $order->save();
            if ($result === false) {
                throw new Exception("order failed", 500);
            }
            $order_id = $order->order_id;
            $this->updateBookCount($orderList);
            foreach ($orderList as $item) {
                $this->createOrderItem($item, $order_id);
            }
            Db::commit();
        } catch (Exception $th) {
            Db::rollback();
            return json([
                'code'  => $th->getCode(),
                'msg'   => $th->getMessage()
            ]);
        }
        return json([
            "statusCode"    => 200,
            "msg"           => "创建订单成功",
            "order_id"      =>  $order_id                //返回订单id
        ]);
    }

    protected function updateBookCount($orderList)
    {
        $bookCount = [];
        foreach ($orderList as $item) {
            $bookCount[$item['order_id']] = $item['count'];
        }
        $keys = array_keys($bookCount);
        $data = Book::all($keys);
        foreach ($data as $item) {
            $res = $item->book_count -= $bookCount[$item->book_id];
            if ($res === false) {
                throw new Exception("update book coun   t failed", 500);
            }
        }
    }



    /**
     * 创建订单项
     *
     * @param Array $item
     * @param String $order_id
     * @return void
     */
    protected function createOrderItem($item, $order_id)
    {
        //创建订单里的物品信息
        $orderItem = new OrderItem([
            'count'     => $item['count'],
            'book_id'   => $item['book_id'],
            'price'     => $item['price'],
            'order_id'  => $order_id,
        ]);

        $result = $orderItem->save();
        if ($result === false) {
            throw new Exception('insert failed', 500);
        }
    }
}
