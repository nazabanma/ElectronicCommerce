<?php

namespace app\api\model;

use think\Model;

class ViewMyCollect extends Model
{

    /**
     * 查询所有收藏
     *
     * @param String $user_id 用户user_id
     * @return json 收藏列表
     */
    public function myCollect($user_id)
    {
        if (is_null($user_id)) {
            return json([
                'code' => '404',
                'msg' => 'user_id is null'
            ]);
        }
        $Collect = new ViewMyCollect();
        $data = $Collect->where('user_id', $user_id)->select();
        return json([
            'code' => '200',
            'data' => $data
        ]);
    }
}
