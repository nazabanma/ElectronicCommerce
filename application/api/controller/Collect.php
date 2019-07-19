<?php

namespace app\api\controller;

use think\Controller;
use think\Request;
use app\api\model\ViewMyCollect;


class Collect extends Controller
{
    private $Collect;

    function _initialize()
    {
        $this->Collect = new \app\api\model\Collect();
    }

    /**
     * 查询所有收藏
     *
     * @param String $user_id 用户user_id
     * @return json 收藏列表
     */
    public function myCollect($user_id)
    {
        $model = new ViewMyCollect();
        return $model->myCollect($user_id);
    }

    /**
     * 添加书本到收藏夹
     *
     * @param Request $request
     * @return void
     */
    public function collectAdd(Request $request)
    {
        return $this->Collect->collectAdd($request);
    }

    /**
     * 删除收藏夹的物品
     *
     * @param Request $request
     * @return void
     */
    public function collectDelete(Request $request)
    {
        return $this->Collect->collectDelete($request);
    }

    public function collectDeleteAll($user_id)
    {
        return $this->Collect->collectDeleteAll($user_id);
    }


    /**
     * 
     * //创建收藏夹
     * @param String $item
     * @param String $user_id
     * @return void
     */
    protected function createCollectItem($item, $user_id)
    {
        return $this->Collect->createCollectItem($item, $user_id);
    }
}
