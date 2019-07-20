<?php

namespace app\api\model;

use think\Model;
use think\Request;

class EvaluateLike extends Model
{

     /**
     * 给评价点赞
     *
     * @param Request $request
     * @return json 点赞结果
     */
    public function evaluateLike(Request $request)
    {
        $evaluate_id = $request->param('evaluate_id');  //评价id
        $user_id = $request->param('user_id');         //用户id

        if (is_null($evaluate_id) || is_null($user_id)) {
            return json([
                'code'  => '404',
                'msg'   => 'Necessary param is null'
            ]);
        }

        $like = new evaluateLike();
        $oldLike =  $like       //首先查询购物车是否有该物品               
                    ->where('user_id', $user_id)
                    ->where('evaluate_id', $evaluate_id)
                    ->find();
        //如果为空，则添加
        if (is_null($oldLike)) {
            $like = new evaluateLike([
                'user_id'       => $user_id,
                'evaluate_id'       => $evaluate_id,
                'like_time'   => date("Y-m-d H:i:s"),
            ]);
            $result = $like->save();
            if ($result === false) {
                return json([
                    'code'  => 500,
                    'msg'   => 'insert failed'
                ]);
            }
            return json([
                "statusCode"    => 200,
                "msg"           => "点赞成功",
            ]);
        }
        //如果非空，则删除
        else {
            $res = $oldLike->delete();
            if ($res === false) {
                return json([
                    'code'  => 500,
                    'msg'   => '取消点赞失败'
                ]);
            }
            return json([
                'code'  => 401,
                'msg'   => '取消点赞成功'
            ]);
        }
    }
    
}
