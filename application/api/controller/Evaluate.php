<?php

namespace app\api\controller;

use think\Controller;
use think\Request;
use app\api\model\ViewBookEvaluate;

class Evaluate extends Controller
{

    private $Evaluate;

    function _initialize()
    {
        $this->Evaluate = new \app\api\model\Evaluate();
    }

    /**
     * 对评价的评论
     *
     * @param Request $request
     * @return json 评价结果
     */
    public function evaluate(Request $request)
    {
        return $this->Evaluate->evaluate($request);
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
    public function evaluateList($book_id, $user_id)
    {
        $model = new ViewBookEvaluate();
        return $model->evaluateList($book_id, $user_id);
    }

    /**
     * 删除评论
     *
     * @param Request $request
     * @return json 删除结果
     */
    public function evaluateDelete($evaluate_id)
    {
        return $this->Evaluate->evaluateDelete($evaluate_id);
    }

    /**
     * 删除评价
     *
     * @param Request $request
     * @return json 删除结果
     */
    public function evaluateDeleteAll($order_item_id)
    {
        return $this->Evaluate->evaluateDeleteAll($order_item_id);
    }

    /**
     * 评价订单
     *
     * @param Request $request
     * @return json 评价结果
     */
    public function evaluateOrder(Request $request)
    {
        return $this->Evaluate->evaluateOrder($request);
    }

    /**
     * 用于上传图片
     *
     * @param Request $request
     * @return json 上传结果
     */
    public function upLoadImg(Request $request)
    {
        return $this->Evaluate->upLoadImg($request);
    }
}
