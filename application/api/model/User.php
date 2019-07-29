<?php

namespace app\api\model;

use think\Model;
use app\api\model\Address;
use app\api\model\Collect;
use think\Request;

class User extends Model
{
    public function shopCart()
    {
        return $this->hasMany('ShopCart', 'user_id');
    }

    public function orderItem()
    {
        return $this->hasManyThrough('OrderItem', 'Order', 'user_id', 'order_id');
    }

    public function book()
    {
        return $this->belongsToMany('Book', 'collect', 'user_id');
    }

    public function collect()
    {
        return $this->hasMany('Collect', 'user_id');
    }

    /**
     * 查询用户
     *
     * @param String $user_id
     * @return json 用户
     */
    public function userInfo($user_id)
    {
        $result = User::get($user_id);
        $result['collect'] = Collect::where('user_id', $user_id)->count();
        $result['address'] = Address::where('user_id', $user_id)->where('flag',1)->count();

        if (empty($result)) {
            return json([
                'code'  => '404',
                'msg'   => 'user  is null'
            ]);
        }

        return json([
            'code'  => 200,
            'data'  => $result
        ]);
    }

    /**
     * 添加用户
     *
     * @param Request $request
     * @return json 添加结果
     */
    public function userAdd(Request $request)
    {
        $User = new User();
        $res = $User->save($request->param());
        $user_id = $User->user_id;
        if ($res === false) {
            return json([
                'code' => 500,
                'msg' => 'insert failed'
            ]);
        }
        return json([
            'code'      => 200,
            'msg'       => 'success',
            'user_id'   => $user_id,
        ]);
    }

    /**
     * 根据openid查找用户
     *
     * @param Request $request
     * @return json 添加结果
     */
    public function userFind($id)
    {
        $User = new User();
        $result = $User->where('open_id', $id)->find();
        if (empty($result)) {
            return json([
                'code'  => '404',
                'msg'   => 'user  is null'
            ]);
        }

        return json([
            'code'  => 200,
            'data'  => $result
        ]);
    }

     /**
     * 用于用户上传头像
     *
     * @param Request $request
     * @return json 上传结果
     */
    public function headImg(Request $request)
    {
        $user_id = $request->param('user_id');  //用户id
        $file = $request->file('file');
        if ($file) {
            $baseUrl = ROOT_PATH . 'public' . DS . 'upload';
            $info = $file->move($baseUrl);
            if ($info) {
                $name = 'http://admin.wyxs.talesrunner.org/upload/' . $info->getSaveName();
                $User = User::get($user_id);
                $User->head_img=$name;
                $res=$User->save();

                if ($res === false) {
                    return json([
                        'code' => 500,
                        'msg' => 'update failed'
                    ]);
                }
                

                return json([
                    'code' => 200,
                    'msg' => '图片上传成功',
                    'name' => $name,
                    'file' => $file
                ]);
            }
            return json([
                'code' => 500,
                'msg' => '图片上传失败',
            ]);
        }
        return json([
            'code' => 500,
            'msg' => '图片上传失败',
        ]);
    }

}
