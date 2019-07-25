<?php

namespace app\api\model;

use think\Model;

class ViewMyOrder extends Model
{
    /**
     * 根据状态id查询用户订单
     *
     * @param String $user_id
     * @param String $state_id
     * @return json 订单信息
     */
    public function myOrder($user_id, $state_id = 'all')
    {
        if (is_null($user_id)) {
            return json([
                'code' => '404',
                'msg' => 'user_id is null'
            ]);
        }

        $Order = new ViewMyOrder();

        $orderList = [];
        if ($state_id == 'all') {
            $orderList = $Order->where('user_id', $user_id)->where('flag', '0')->select();
        } else {
            $orderList = $Order->where('user_id', $user_id)->where('flag', '0')->where('order_state_id', $state_id)->select();
        }

        if (empty($orderList)) {
            return json([
                'code' => '404',
                'msg' =>   "无评价",
            ]);
        }

        // 返回结果
        $data = [];
        // 订单项数组
        $item = [];
        $prev = -1;
        foreach ($orderList as $orderItem) {
            $temp = [
                'order_id'          => $orderItem['order_id'],
                'time'              => $orderItem['create_time'],
                'order_state_id'    => $orderItem['order_state_id'],
                'order_item_id'     => $orderItem['order_item_id'],
                'book_id'           => $orderItem['book_id'],
                'book_name'         => $orderItem['book_name'],
                'book_author'       => $orderItem['book_author'],
                'book_img'          => explode(';', $orderItem['book_img'])[0],
                'price'             => $orderItem['price'],
                'count'             => $orderItem['count'],
                'user_id'           => $orderItem['user_id'],
                'flag'              => $orderItem['flag'],

            ];
            if ($prev > 0 && $prev != $orderItem['order_id']) {
                array_push($data, $this->getOrderItem($item));
                $item = [];
            }
            //相当于array_push
            $item[] = $temp;
            $prev = $orderItem['order_id'];
        }
        array_push($data, $this->getOrderItem($item));
        return json([
            'code' => '200',
            'data' => $data,
        ]);
    }
    /**
     * 获得一个订单的物品
     *
     * @param array $item 该订单的物品
     * @return array
     */
    protected function getOrderItem($item)
    {
        return [
            'order_id'          => $item[0]['order_id'],
            'time'              => $item[0]['time'],
            'order_state_id'    => $item[0]['order_state_id'],
            'user_id'           => $item[0]['user_id'],
            'flag'              => $item[0]['flag'],
            'item'              => $item,
        ];
    }




    /**
     * 查询所有订单
     *
     * @param String $user_id
     * @return json 订单信息
     */
    public function allOrder()
    {
        $Order = new ViewMyOrder();
        $data = $Order->where('flag', '0')->select();
        return json([
            'code' => '200',
            'data' => $data
        ]);
    }
}
