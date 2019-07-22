<?php

namespace app\admin\model;

use think\Model;

class User extends Model
{


    /**
     * 查询用户列表
     *
     * @return json
     */
    public static function userList()
    {
        return json([
            'code'  => 200,
            'data'  => User::all(),
        ]);
    }

    /**
     * 查找某用户具体信息
     *
     * @param String $user_id
     * @return json
     */
    public static function findUser($user_id)
    {
        paramValidata($user_id);
        return json([
            'code'  => 200,
            'data'  => User::get($user_id),
        ]);
    }

    /**
     * 删除指定用户
     *
     * @param String $user_id
     * @return json
     */
    public static function delUser($user_id)
    {
        paramValidata($user_id);
        $res = User::destroy($user_id);
        if ($res === false) {
            return json([
                'code'  => 500,
                'msg'   => 'delete failed'
            ]);
        }
        return json([
            'code'  => 200,
            'msg'   => 'delete success',
        ]);
    }
}
