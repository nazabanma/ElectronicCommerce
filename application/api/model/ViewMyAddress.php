<?php

namespace app\api\model;

use think\Model;

class ViewMyAddress extends Model
{

    /**
     * 查询所有地址
     *
     * @param String $user_id
     * @return json 收货地址列表
     */
    public function myAddress($user_id)
    {
        if (is_null($user_id)) {
            return json([
                'code' => '404',
                'msg' => 'Necessary param is null'
            ]);
        }
        $Address = new ViewMyAddress();
        $data = $Address->where('user_id', $user_id)->select();
        return json([
            'code' => '200',
            'data' => $data
        ]);
    }
}
