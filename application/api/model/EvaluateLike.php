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
        $user_id = $request->param('$user_id');         //用户id

        if (is_null($evaluate_id) || is_null($user_id)) {
            return json([
                'code'  => '404',
                'msg'   => 'Necessary param is null'
            ]);
        }

        $like = new EvaluateLike([
            'user_id'         => $user_id,
            'evaluate_id'     => $evaluate_id,
            'like_time'       => date("Y-m-d H:i:s"),
        ]);

        $result = $like->save();
        if ($result === false) {
            return json([
                'code'  => 500,
                'msg'   => 'update failed'
            ]);
        }
        return json([
            "statusCode"    => 200,
            "msg"           => "点赞成功",
        ]);
    }
}
