<?php

namespace app\api\model;

use think\Model;

class ViewMyOrder extends Model
{
    /**
     * 查询所有订单
     *
     * @param String $user_id
     * @return json 订单信息
     */
    public function myOrder($user_id)
    {
        if (is_null($user_id)) {
            return json([
                'code' => '404',
                'msg' => 'user_id is null'
            ]);
        }
        $Order = new ViewMyOrder();
        $data = $Order->where('user_id', $user_id)->where('flag', '0')->select();
        return json([
            'code' => '200',
            'data' => $data
        ]);
    }
}
