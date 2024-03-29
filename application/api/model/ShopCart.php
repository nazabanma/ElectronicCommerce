<?php

namespace app\api\model;

use think\Request;
use think\Model;
use think\Db;
use app\api\model\Collect;

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
         //首先查询购物车是否有该物品    
        $oldCart =  $shopCart                 
            ->where('user_id', $user_id)
            ->where('book_id', $book_id)
            ->find();
        $result = '';
        //如果是空的，则直接添加到购物车
        if (empty($oldCart)) {
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
        if ($res) {
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
     * 删除购物车所有物品
     *
     * @param String $user_id
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
        if (empty($oldCart)) {
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

    /**
     * 从购物车选择书本到收藏夹
     *
     * @param Request $request
     * @return void
     */
    public function removeToCollect(Request $request)
    {
        $carts = json_decode($request->param('carts'), true);   //用户选择的所有书本
        $user_id = $request->param('user_id');
        $oldCollect = Collect::where('user_id', $user_id)->column('book_id'); //查询收藏夹的书本

        if (empty($carts) || is_null($user_id)) {
            return json([
                'code'  => '404',
                'msg'   => 'Necessary param is null'
            ]);
        }

        try {
            Db::startTrans();
            foreach ($carts as $item) {

                if (\in_array($item, $oldCollect)) {   //如果存在收藏夹，则跳过
                    continue;
                }

                $this->createCollectItem($item, $user_id);
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
            "msg"           => "加入收藏夹成功",
        ]);
    }

    /**
     * 
     * //创建收藏夹物品
     * @param String $item
     * @param String $user_id
     * @return void
     */
    protected function createCollectItem($item, $user_id)
    {
        $collectItem = new Collect([
            'book_id'           => $item,
            'create_time'       => date("Y-m-d H:i:s"),
            'user_id'           => $user_id,
        ]);

        $result = $collectItem->save();
        if ($result === false) {
            throw new Exception('insert failed', 500);
        }
    }
}
