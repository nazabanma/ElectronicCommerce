<?php

namespace app\api\controller;

use think\Controller;
use think\Request;
use app\api\model\ViewShopCart;
use app\api\model\ShopCart;

class Cart extends Controller
{

    private $Cart;

    function _initialize()
    {
        $this->Cart = new \app\api\model\ShopCart();
    }
    /**
     * 添加书本到购物车
     *
     * @param Request $request
     * @return void
     */
    public function cartAdd(Request $request)
    {
        return $this->Cart->cartAdd($request);
    }

    /**
     * 删除购物车的物品
     *
     * @param Request $request
     * @return void
     */
    public function cartDelete(Request $request)
    {
        return $this->Cart->cartDelete($request);
    }

    public function cartDeleteAll($user_id)
    {
        return $this->Cart->cartDeleteAll($user_id);
    }


    /**
     * 更新购物车的书本信息
     *
     * @param Request $request
     * @return void
     */
    public function cartUpdate(Request $request)
    {
        return $this->Cart->cartUpdate($request);
    }

    /**
     * 根据用户id返回购物车列表
     * 
     * @param String $user_id 
     * @return 购物车数组
     */
    public function shopCart($user_id)
    {
        $model = new ViewShopCart();
        return $model->shopCart($user_id);
    }

     /**
     * 从购物车选择书本到收藏夹
     *
     * @param Request $request
     * @return void
     */
    public function removeToCollect(Request $request)
    {
        $model = new ShopCart();
        return $model->removeToCollect($request);
    }

    
}
