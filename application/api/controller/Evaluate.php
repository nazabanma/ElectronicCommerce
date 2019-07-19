<?php

namespace app\api\controller;

use think\Controller;
use think\Request;
use app\api\model\ViewBookEvaluate;
 
class Evaluate extends Controller
{


 /**
     * 评价订单，也可用于对评价的评论
     *
     * @param Request $request
     * @return json 评价结果
     */
    public function evaluate(Request $request)
    {
        $model = new \app\api\model\Evaluate();
        return $model->evaluate($request);
    }

     /**
     * 给评价点赞
     *
     * @param Request $request
     * @return json 点赞结果
     */
    public function evaluateLike(Request $request)
    {
        $model = new \app\api\model\EvaluateLike();
        return $model->evaluateLike($request);

    }

    /**
     * 根据书本id返回对应评价
     *
     * @param String $book_id
     * @return json
     */
    public function evaluateList($book_id)
    {
        $model = new ViewBookEvaluate();
        return $model->evaluateList($book_id);
    }


}