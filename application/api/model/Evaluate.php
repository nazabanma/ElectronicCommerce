<?php

namespace app\api\model;

use think\Request;
use think\Model;
use think\Db;

class Evaluate extends Model
{

    /**
     * 对评价的评论
     *
     * @param Request $request
     * @return json 评价结果
     */
    public function evaluate(Request $request)
    {
        $order_item_id = $request->param('order_item_id');     //订单项id
        $user_id = $request->param('user_id');                //用户id
        $content = $request->param('content');                 //评价内容
        $time = $request->param('time');

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
        if ($result === 1) {
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


        $result = Evaluate::where('order_item_id', $order_item_id)->delete();
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

    /**
     * 评价订单
     *
     * @param Request $request
     * @return json 评价结果
     */
    public function evaluateOrder(Request $request)
    {
        $user_id = $request->param('user_id');  //用户id
        $evaluate_list = json_decode($request->param('evaluate_list'), true);
        $if_anonymous = $request->param('if_anonymous'); //是否匿名
        if (empty($evaluate_list)) {
            return json([
                'code'  => '404',
                'msg'   => 'Necessary param is null'
            ]);
        }

        try {
            Db::startTrans();
            foreach ($evaluate_list as $item) {

                $this->createEvaluateItem($user_id, $item,$if_anonymous);
            }

            Db::commit();
        } catch (Exception $th) {
            Db::rollback();
            return json([
                'code'  => $th->getCode(),
                'msg'   => $th->getMessage()
            ]);
        }
        return json([
            "statusCode"    => 200,
            "msg"           => "评价成功",
        ]);
    }

    /**
     * 
     * //创建购物车项
     * @param String $item
     * @param String $user_id
     * @return void
     */
    protected function createEvaluateItem($user_id, $item,$if_anonymous)
    {

        $evaluateItem = new Evaluate([
            'user_id'           => $user_id,
            'order_item_id'     => $item['order_item_id'],
            'content'           => $item['content'],
            'img'               => $item['img'],
            'if_anonymous'      => $if_anonymous,
            'evaluate_time'     => date("Y-m-d H:i:s"),

        ]);

        $result = $evaluateItem->save();
        if ($result === false) {
            throw new Exception('insert failed', 500);
        }
    }



    /**
     * 用于上传图片
     *
     * @param Request $request
     * @return json 上传结果
     */
    public function upLoadImg(Request $request)
    {
        $file = $request->file('file');
        if ($file) {
            $baseUrl = ROOT_PATH . 'public' . DS . 'upload';
            $info = $file->move($baseUrl);
            if ($info) {
                $name = 'http://admin.wyxs.talesrunner.org/upload/' . $info->getSaveName();
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
