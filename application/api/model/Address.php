<?php

namespace app\api\model;

use think\Model;
use think\Request;

class Address extends Model
{
    /**
     * 编辑收货地址
     *
     * @param Request $request
     * @return json 编辑结果
     */
    public static function editAddress(Request $request)
    {
        $address_id = $request->param('address_id');
        if (is_null($address_id)) {
            return json([
                'code' => 404,
                'msg' => 'Necessary param is null'
            ]);
        }
        $Address = Address::get($address_id);
        if (!$Address) {
            return json([
                'code' => 404,
                'msg' => '该记录不存在'
            ]);
        }
        //通过两次键值翻转,去除数组中的索引部分,否则更新数据时会将索引识别为字段
        $data = array_flip(array_flip($request->param()));
        $res = $Address->save($data);
        if ($res === false) {
            return json([
                'code' => 500,
                'msg' => 'update failed'
            ]);
        }
        return json([
            'code' => 200,
            'msg' => 'success'
        ]);
    }
}
