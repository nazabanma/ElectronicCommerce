<?php

namespace app\api\model;

use think\Request;
use think\Model;

class ShopCart extends Model
{

    /**
     * 添加书本到购物车
     *
     * @param Request $request
     * @return void
     */
    public function cartAdd(Request $request)
    {
        $book_id = $request->param()['book_id'];   //书本id
        $user_id = $request->param()['user_id'];   //用户id
        if (is_null($user_id) || is_null($book_id)) {
            return json([
                'code'  => '401',
                'msg'   => 'Necessary param is null'
            ]);
        }
        $shopCart = new ShopCart();
        $oldCart =  $shopCart       //首先查询购物车是否有该物品               
            ->where('user_id', $user_id)
            ->where('book_id', $book_id)
            ->find();
        $result = '';
        //如果是空的，则直接添加到购物车
        if (is_null($oldCart)) {
            $cart = new ShopCart([
                'user_id'       => $user_id,
                'book_id'       => $book_id,
                'create_time'   => date("Y-m-d H:i:s"),
                'count'         => 1
            ]);
            $result = $cart->save();
        }
        //如果不是空的，则存在购物车的该物品数量加1
        else {
            $oldCart->count++;
            $result = $oldCart->save();
        }
        if ($result === false) {
            return json([
                'code'  => 500,
                'msg'   => 'update failed'
            ]);
        }
        return json([
            "statusCode"    => 200,
            "msg"           => "添加成功",
        ]);
    }

    /**
     * 删除购物车的物品
     *
     * @param Request $request
     * @return void
     */
    public function cartDelete(Request $request)
    {
        $carts = json_decode($request->param('carts'), true);   //用户选择的所有书本
        $res = ShopCart::destroy($carts);
        if ($res === false) {
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
     * 删除购物车的物品
     *
     * @param Request $request
     * @return void
     */
    public function cartDeleteAll($user_id)
    {
        if (is_null($user_id)) {
            return json([
                'code'  => '401',
                'msg'   => 'Necessary param is null'
            ]);
        }
        $shopCart = new ShopCart();
        $oldCart = $shopCart->where('user_id', $user_id);
        $result = $oldCart->delete();
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
     * 更新购物车的书本信息
     *
     * @param Request $request
     * @return void
     */
    public function cartUpdate(Request $request)
    {
        $cart_id = $request->param()['cart_id'];   //用户id
        $count = $request->param()['count'];   //数量
        if (is_null($cart_id))
            return json([
                'code'  => '404',
                'msg'   => 'Necessary param is null'
            ]);

        $oldCart = ShopCart::get($cart_id);
        //如果是空的
        if (is_null($oldCart)) {
            return json([
                'code'  => 401,
                'msg'   => '不存在该物品'
            ]);
        }
        //如果不是空的，则添加数量
        $oldCart->count = $count;
        $result = $oldCart->save();
        if ($result === false) {
            return json([
                'code'  => 500,
                'msg'   => 'update failed'
            ]);
        }
        return json([
            "statusCode"    => 200,
            "msg"           => "更新成功",
        ]);
    }
}
