<?php

namespace app\api\model;

use think\Request;
use think\Model;

class Collect extends Model
{



    /**
     * 添加书本到收藏夹
     *
     * @param Request $request
     * @return void
     */
    public function collectAdd(Request $request)
    {
        $book_id = $request->param()['book_id'];   //用户收藏的书本id
        $user_id = $request->param()['user_id'];   //用户id
        if (is_null($user_id) || is_null($book_id))
            return json([
                'code'  => '401',
                'msg'   => '请求参数有误'
            ]);
        $collect = new Collect();
        $oldCollect =  $collect       //首先查询购物车是否有该物品               
            ->where('user_id', $user_id)
            ->where('book_id', $book_id)
            ->find();
        //如果为空，则添加
        if (is_null($oldCollect)) {
            $collect = new Collect([
                'user_id'       => $user_id,
                'book_id'       => $book_id,
                'create_time'   => date("Y-m-d H:i:s"),
            ]);
            $result = $collect->save();
            if ($result === false) {
                return json([
                    'code'  => 500,
                    'msg'   => 'insert failed'
                ]);
            }
            return json([
                "statusCode"    => 200,
                "msg"           => "添加成功",
            ]);
        }
        //如果非空，则删除
        else {
            $res = $oldCollect->delete();
            if ($res === false) {
                return json([
                    'code'  => 500,
                    'msg'   => '取消收藏失败'
                ]);
            }
            return json([
                'code'  => 401,
                'msg'   => '取消收藏成功'
            ]);
        }
    }


    /**
     * 删除收藏夹的物品
     *
     * @param Request $request
     * @return void
     */
    public function collectDelete(Request $request)
    {
        $Collects = json_decode($request->param('collects'), true);   //用户选择的所有书本
        $res = Collect::destroy($Collects);
        if ($res === false) {
            return json([
                'code'  => 500,
                'msg'   => 'delete failed'
            ]);
        }
        return json([
            "statusCode"    => 200,
            "msg"           => "删除成功",
        ]);
    }

    public function collectDeleteAll($user_id)
    {
        if (is_null($user_id)) {
            return json([
                'code'  => '401',
                'msg'   => 'Necessary param is null'
            ]);
        }
        $Collect = new Collect();
        $oldCollect = $Collect->where('user_id', $user_id);
        $result = $oldCollect->delete();
        if ($result === false) {
            return json([
                'code'  => 500,
                'msg'   => 'delete failed'
            ]);
        }
        return json([
            "statusCode"    => 200,
            "msg"           => "删除成功",
        ]);
    }
}
