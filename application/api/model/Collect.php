<?php

namespace app\api\model;

use think\Request;
use think\Model;
use app\api\model\ShopCart;
use think\Db;

class Collect extends Model
{



    /**
     * 添加书本到收藏夹
     *
     * @param Request $request
     * @return void
     */
    public function collectAdd(Request $request)
    {
        $book_id = $request->param()['book_id'];   //用户收藏的书本id
        $user_id = $request->param()['user_id'];   //用户id
        if (is_null($user_id) || is_null($book_id))
            return json([
                'code'  => '401',
                'msg'   => '请求参数有误'
            ]);
        $collect = new Collect();

        //首先查询收藏夹是否有该物品     
        $oldCollect =  $collect                 
            ->where('user_id', $user_id)
            ->where('book_id', $book_id)
            ->find();
        //如果为空，则添加
        if (empty($oldCollect)) {
            $collect = new Collect([
                'user_id'       => $user_id,
                'book_id'       => $book_id,
                'create_time'   => date("Y-m-d H:i:s"),
            ]);
            $result = $collect->save();
            if ($result === false) {
                return json([
                    'code'  => 500,
                    'msg'   => 'insert failed'
                ]);
            }
            return json([
                "statusCode"    => 200,
                "msg"           => "添加成功",
            ]);
        }
        //如果非空，则删除
        else {
            $res = $oldCollect->delete();
            if ($res === false) {
                return json([
                    'code'  => 500,
                    'msg'   => '取消收藏失败'
                ]);
            }
            return json([
                'code'  => 401,
                'msg'   => '取消收藏成功'
            ]);
        }
    }


    /**
     * 删除收藏夹的物品
     *
     * @param Request $request
     * @return void
     */
    public function collectDelete(Request $request)
    {
        $Collects = json_decode($request->param('collects'), true);   //用户选择的所有书本
        if (empty($Collects)) {
            return json([
                'code'  => 404,
                'msg'   => 'Necessary param is empty'
            ]);
        }
        $res = Collect::destroy($Collects);
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

    public function collectDeleteAll($user_id)
    {
        if (is_null($user_id)) {
            return json([
                'code'  => '401',
                'msg'   => 'Necessary param is null'
            ]);
        }
        $Collect = new Collect();
        $oldCollect = $Collect->where('user_id', $user_id);
        $result = $oldCollect->delete();
        if ($result) {
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
     * 从收藏夹选择书本到购物车
     *
     * @param Request $request
     * @return void
     */
    public function removeToCart(Request $request)
    {
        $collects = json_decode($request->param('collects'), true);   //用户选择的所有书本
        $user_id = $request->param('user_id');
        $oldCart = ShopCart::where('user_id', $user_id)->column('book_id'); //查询购物车已有的书本

        if (empty($collects) || is_null($user_id)) {
            return json([
                'code'  => '404',
                'msg'   => 'Necessary param is null'
            ]);
        }

        try {
            Db::startTrans();
            foreach ($collects as $item) {

                if (\in_array($item, $oldCart)) {   //当购物车已经存在该书本，则跳过
                    continue;
                }

                $this->createCartItem($item, $user_id);  //当不存在则添加
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
            "msg"           => "加入购物车成功",
        ]);
    }

    /**
     * 
     * //创建购物车项
     * @param String $item
     * @param String $user_id
     * @return void
     */
    protected function createCartItem($item, $user_id)
    {

        $cartItem = new ShopCart([
            'book_id'           => $item,
            'create_time'       => date("Y-m-d H:i:s"),
            'user_id'           => $user_id,
            'count'             => 1
        ]);

        $result = $cartItem->save();
        if ($result === false) {
            throw new Exception('insert failed', 500);
        }
    }

}
