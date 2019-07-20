<?php

namespace app\api\model;
use think\Request;
use think\Model;

class Evaluate extends Model
{ 

      /**
     * 评价订单，也可用于对评价的评论
     *
     * @param Request $request
     * @return json 评价结果
     */
    public function evaluate(Request $request)
    {
           $order_item_id=$request->param('order_item_id');     //订单项id
           $user_id=$request->param('user_id');                //用户id
           $content=$request->param('content');                 //评价内容
           $img='url';                                          //需要一个接收图片的方法并返回url

           if (is_null($order_item_id) || is_null($user_id)) {
            return json([
                'code'  => '404',
                'msg'   => 'Necessary param is null'
            ]);
        }

           $evaluate = new Evaluate([
            'user_id'           => $user_id,
            'order_item_id'     => $order_item_id,
            'content'           => $content,
            'img'               => $img,
            'evaluate_time'     =>date("Y-m-d H:i:s"),
        ]);

        $result = $evaluate->save();
        if ($result === false) {
            return json([
                'code'  => 500,
                'msg'   => 'update failed'
            ]);
        }
        return json([
            "statusCode"    => 200,
            "msg"           => "评价成功",
        ]);


    }


}
