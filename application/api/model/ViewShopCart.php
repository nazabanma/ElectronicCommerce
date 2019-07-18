<?php

namespace app\api\model;

use think\Model;

class ViewShopCart extends Model
{
    /**
     * 根据用户id返回购物车列表
     * 
     * @param String $user_id 
     * @return 购物车数组
     */
    public function shopCart($user_id)
    {
        if (is_null($user_id)) {
            return json([
                'code' => '404',
                'msg' => 'Necessary param is null'
            ]);
        }
        $cart = new ViewShopCart();
        $list = $cart->where('user_id', $user_id)->select();
        return json([
            'code' => '200',
            'data' => $list
        ]);
    }
}
