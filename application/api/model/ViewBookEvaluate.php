<?php

namespace app\api\model;

use think\Model;
use app\api\model\EvaluateLike;

class ViewBookEvaluate extends Model
{
    /**
     * 根据书本id返回对应评价
     *
     * @param String $book_id
     * @return json
     */
    public function evaluateList($book_id, $user_id)
    {
        if (is_null($book_id) || is_null($user_id)) {
            return json([
                'code' => '404',
                'msg' => 'book_id is null'
            ]);
        }
        //查询点赞
        $oldLike = EvaluateLike::where('user_id', $user_id)->column('evaluate_id');

        $evaluate = new ViewBookEvaluate();
        $evaluateList = $evaluate->where('book_id', $book_id)->select();
        if (empty($evaluateList)) {
            return json([
                'code' => '404',
                'msg' =>   "无评价",
            ]);
        }
        // 返回结果
        $data = [];
        // 对评价的评论的顺组
        $comment = [];
        //不能直接等于evaluateList[0]['order_item_id'],否则若数组为空则报错
        $prev = -1;
        foreach ($evaluateList as $evaluateItem) {
           
            if(is_null($evaluateItem['content'])&&is_null($evaluateItem['img']))
            {
                continue;
            }

            $temp = [
                'evaluate_id'   => $evaluateItem['evaluate_id'],
                'name'          => $evaluateItem['nick_name'],
                'content'       => $evaluateItem['content'],
                'time'          => $evaluateItem['evaluate_time'],
                'order_item_id' => $evaluateItem['order_item_id'],
                'like_count'    => EvaluateLike::where('evaluate_id', $evaluateItem['evaluate_id'])->count(),
                'if_like'       => in_array($evaluateItem['evaluate_id'], $oldLike) ? 1 : 0,
                'img'           => $evaluateItem['img'],
                'if_anonymous'  => $evaluateItem['if_anonymous'],
                'head_img'      => $evaluateItem['head_img'],
                'user_id'       => $evaluateItem['user_id'],
                'if_me'         => $evaluateItem['user_id'] == $user_id ? 1 : 0
            ];
            if ($prev > 0 && $prev != $evaluateItem['order_item_id']) {
                array_push($data, $this->getEvaluateItem($comment));
                $comment = [];
            }
            //相当于array_push
            $comment[] = $temp;
            $prev = $evaluateItem['order_item_id'];
        }
        array_push($data, $this->getEvaluateItem($comment));
        return json([
            'code' => '200',
            'data' => $data,
        ]);
    }
    /**
     * 获得一个订单项的评价及评论
     *
     * @param array $comment 该订单项下的所有评价
     * @return array
     */
    protected function getEvaluateItem($comment)
    {
        return [
            'evaluate_id'       => $comment[0]['evaluate_id'],
            'name'              => $comment[0]['name'],
            'content'           => $comment[0]['content'],
            'time'              => $comment[0]['time'],
            'order_item_id'     => $comment[0]['order_item_id'],
            'like_count'        => $comment[0]['like_count'],
            'if_like'           => $comment[0]['if_like'],
            'img'               => $comment[0]['img'],
            'if_anonymous'      => $comment[0]['if_anonymous'],
            'head_img'          => $comment[0]['head_img'],
            'user_id'           => $comment[0]['user_id'],
            'if_me'             => $comment[0]['if_me'],
            'comment'           => array_splice($comment, 1),
        ];
    }
}
