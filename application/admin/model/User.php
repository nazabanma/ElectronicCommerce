<?php

namespace app\admin\model;

use think\Model;

class User extends Model
{
    public static function userList()
    {
        return json([
            'code'  => 200,
            'data'  => User::all(),
        ]);
    }

    public static function findUser($user_id)
    {
        paramValidata($user_id);
        return json([
            'code'  => 200,
            'data'  => User::get($user_id),
        ]);
    }
}
