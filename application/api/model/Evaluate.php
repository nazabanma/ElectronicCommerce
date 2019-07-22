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
           $time=$request->param('time'); 
           $if_anonymous=$request->param('if_anonymous');
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
            'if_anonymous'      => $if_anonymous,
            'evaluate_time'     => $time,
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

     /**
     * 删除评论
     *
     * @param Request $request
     * @return json 删除结果
     */
    public function evaluateDelete($evaluate_id)
    {

           if (is_null($evaluate_id)) {
            return json([
                'code'  => '404',
                'msg'   => 'Necessary param is null'
            ]);
        }


        $result = Evaluate::destroy($evaluate_id);
        if ($result ===1) {
            return json([
                "statusCode"    => 200,
                "msg"           => "删除成功",
            ]);
        }
        return json([
            'code'  => 500,
                'msg'   => 'delete failed'
        ]);


    }

     /**
     * 删除评论
     *
     * @param Request $request
     * @return json 删除结果
     */
    public function evaluateDeleteAll($order_item_id)
    {

           if (is_null($order_item_id)) {
            return json([
                'code'  => '404',
                'msg'   => 'Necessary param is null'
            ]);
        }


        $result = Evaluate::where('order_item_id',$order_item_id)->delete();
        if ($result===false) {
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
