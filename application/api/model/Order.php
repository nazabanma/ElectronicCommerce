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
    public function orderCreate(Request $request)
    {
        $orderList = json_decode($request->param()['order_list'], true);  //接收到的订单数组
        $user_id = $request->param()['user_id'];                    //用户id
        $address_id = $request->param()['address_id'];              //地址id
        $remark = $request->param()['remark'];                      //订单备注

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
            $this->updateBookCount($orderList, -1);   //更新书本数量
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
            "code"          => 200,
            "msg"           => "创建订单成功",
            "order_id"      =>  $order_id                //返回订单id
        ]);
    }


    /**
     * 更新书本
     * @param Array $orderList 订单项集合
     * @param String $sign 加减的记号
     * @return 更新结果
     */
    protected function updateBookCount($orderList, $sign)
    {
        if (empty($orderList)) {
            throw new Exception("order is not exited", 404);
        }
        $bookCount = [];
        foreach ($orderList as $item) {
            $bookCount[$item['book_id']] = $sign * $item['count'];
        }
        $keys = array_keys($bookCount);
        $data = Book::all($keys);
        foreach ($data as $item) {
            $item->book_count += $bookCount[$item->book_id];
            $res = $item->save();
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




    /**
     * 修改订单地址信息(要在发货前修改)
     *
     * @param Request $request
     * @return void
     */
    public function orderUpdate(Request $request)
    {
        $order_id = $request->param()['order_id'];                   //订单id
        $address_id = $request->param()['address_id'];         //用户修改的地址id

        if (is_null($address_id)||is_null($order_id)) {
            return json([
                'code'  => '404',
                'msg'   => 'Necessary param is null'
            ]);
        }
        $order = new Order();
        $order = $order->where('order_id', $order_id)->find();

        if (empty($order))

            return json([
                'code'  => '401',
                'msg'   => '订单不存在'
            ]);

        if ($order->order_state_id != 1)
            return json([
                'code'  => '401',
                'msg'   => '订单不是待发货状态，无法修改'
            ]);

        $order->address_id = $address_id;

        $result = $order->save();
        if ($result === false) {
            return json([
                'code'  => 500,
                'msg'   => 'update failed'
            ]);
        }
        return json([
            "code"          => 200,
            "msg"           => "修改订单成功",
        ]);
    }


    /**
     * 修改订单状态（用户确认收货，商家发货）
     *
     * @param Request $request
     * @return void
     */
    public function orderStateUpdate(Request $request)
    {
        $order_id = $request->param()['order_id'];
        $order_state_id = $request->param()['order_state_id'];
        if (is_null($order_id) || is_null($order_state_id)) {
            return json([
                'code'  => '404',
                'msg'   => 'Necessary param is null'
            ]);
        }
        $order = new Order();
        $order = $order->where('order_id', $order_id)->find();

        if (is_null($order))

            return json([
                'code'  => '401',
                'msg'   => '订单不存在'
            ]);

        $order->order_state_id = $order_state_id;
        $result = $order->save();
        if ($result === false) {
            return json([
                'code'  => 500,
                'msg'   => 'update failed'
            ]);
        }
        return json([
            "code"          => 200,
            "msg"           => "修改订单状态成功",
        ]);
    }

    /**
     * 用户查看订单列表删除订单
     *
     * @param Request $request
     * @return void
     */
    public function orderDelete(Request $request)
    {
        $order_id = $request->param()['order_id'];   //用户删除的订单id
        if (is_null($order_id)) {
            return json([
                'code'  => '404',
                'msg'   => 'Necessary param is null'
            ]);
        }
        $order = new Order();
        $order = $order->where('order_id', $order_id)->find();

        if (empty($order)) {
            return json([
                'code'  => '401',
                'msg'   => '订单不存在'
            ]);
        }

        $order->flag = 1;           //1表示用户删除订单，商家仍然可看见订单
        $result = $order->save();
        if ($result === false) {
            return json([
                'code'  => 500,
                'msg'   => 'update failed'
            ]);
        }

        return json([
            "code"          => 200,
            "msg"           => "修改订单状态成功",
        ]);
    }


    /**
     * 用户取消订单
     *
     * @param Request $request
     * @return void
     */
    public function orderCancel(Request $request)
    {
        $order_id = $request->param('order_id');  //订单id
        $detail = $request->param('detail');  //订单id

        if (is_null($order_id)||is_null($detail)) {
            return json([
                'code'  => '404',
                'msg'   => 'Necessary param is null'
            ]);
        }
        $order = new Order();
        $order = $order->where('order_id', $order_id)->find();

        if (empty($order)) {
            return json([
                'code'  => '401',
                'msg'   => '订单不存在'
            ]);
        }

         if($order->order_state_id==5)
         {
            return json([
                'code'  => '402',
                'msg'   => '订单已取消'
            ]);
         }

        Db::startTrans();
        try {
            $order->order_state_id = 5; //订单状态id改成取消订单id
            $result = $order->save();
            if ($result === false) {
                Db::rollback();
            }
            $orderItem = new OrderItem();
            $orderList = $orderItem->where('order_id', $order_id)->select();
            $this->updateBookCount($orderList, 1);
            $OrderCancel = new OrderCancel();
            $res = $OrderCancel->save([
                'detail'    => $detail,
                'order_id'  => $order_id
            ]);
            if ($res === false) {
                throw new Exception("cancel insert failed", 500);
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
            'code'  => 200,
            'msg'   => '取消成功'
        ]);
    }
}
