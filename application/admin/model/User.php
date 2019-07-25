<?php

namespace app\admin\model;

use think\Model;
use think\Request;

class User extends Model
{


    /**
     * 查询用户列表
     *
     * @return json
     */
    public static function userList($page)
    {
        $model = new User();
        return json([
            'code'  => 200,
            'data'  => $model->limit(($page - 1) * 10, 10)->select(),
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
        return json([
            'code'  => 200,
            'data'  => User::get($user_id),
        ]);
    }

    /**
     * 模糊查询
     *
     * @param Request $request
     * @return json
     */
    public static function findUserFuzzy(Request $request)
    {
        $user_id = $request->param('user_id');
        $nick_name = $request->param('nick_name');
        $User = new User();
        $data = $User->where('user_id', 'like', '%' . $user_id . '%')
            ->where('nick_name', 'like', '%' . $nick_name . '%')
            ->select();
        return json([
            'code'  => 200,
            'data'  => $data,
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
        if ($res == 0) {
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
