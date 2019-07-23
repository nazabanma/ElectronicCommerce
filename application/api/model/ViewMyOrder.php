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
        $data = [];
        if ($state_id == 'all') {
            $data = $Order->where('user_id', $user_id)->where('flag', '0')->select();
        } else {
            $data = $Order->where('user_id', $user_id)->where('flag', '0')->where('order_state_id', $state_id)->select();
        }
        return json([
            'code' => '200',
            'data' => $data
        ]);
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
