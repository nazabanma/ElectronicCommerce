<?php

namespace app\api\controller;

use think\Controller;
use think\Request;
use think\Config;
use think\Exception;
use think\Db;
use app\api\model\Address;
use app\api\model\BookType;
use app\api\model\Order;
use app\api\model\OrderItem;
use app\api\model\EvaluateLike;
use app\api\model\Collect;
use app\api\model\ShopCart;
use app\api\model\OrderCancel;
use app\api\model\ViewMyCollect;
use app\api\model\ViewMyOrder;
use app\api\model\ViewBookList;
use app\api\model\ViewBookDetail;
use app\api\model\ViewBookEvaluate;
use app\api\model\ViewMyAddress;
use app\api\model\ViewShopCart;
use app\api\model\Book;


class User extends Controller
{


    public function login(Request $request)
    {
        $username = $request->param('username');
        $password = $request->param('password');

        if (is_null($password) || is_null($username)) {
            return json(["code" => 400, "msg" => "用户名或密码为空"]);
        }

        $User = new \app\api\model\User();
        $res = $User
            ->field("uid,password")
            ->where('username', $username)
            ->find();

        if (!$res) {
            return json(['code' => 404, 'msg' => '用户不存在']);
        }
        if ($res->password != $password) {
            return json(['code' => 401, 'msg' => '密码错误']);
        }
        if ($res->password == $password) {
            return json(['code' => 200, 'msg' => 'ok', 'data' => $res->uid]);
        }
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
     * 查询所有订单
     *
     * @param String $user_id
     * @return json 订单信息
     */
    public function myOrder($user_id)
    {
        $model = new ViewMyOrder();
        return $model->myOrder($user_id);
    }

    /**
     * 查询所有地址
     *
     * @param String $user_id
     * @return json 收货地址列表
     */
    public function myAddress($user_id)
    {
        if (is_null($user_id)) {
            return json([
                'code' => '404',
                'msg' => 'Necessary param is null'
            ]);
        }
        $Address = new ViewMyAddress();
        $data = $Address->where('user_id', $user_id)->select();
        return json([
            'code' => '200',
            'data' => $data
        ]);
    }

    /**
     * 编辑收货地址
     *
     * @param Request $request
     * @return json 编辑结果
     */
    public function editAddress(Request $request)
    {
        $address_id = $request->param('address_id');
        if (is_null($address_id)) {
            return json([
                'code' => 404,
                'msg' => 'Necessary param is null'
            ]);
        }
        $Address = Address::get($address_id);
        if (!$Address) {
            return json([
                'code' => 404,
                'msg' => '该记录不存在'
            ]);
        }
        //通过两次键值翻转,去除数组中的索引部分,否则更新数据时会将索引识别为字段
        $data = array_flip(array_flip($request->param()));
        $res = $Address->save($data);
        if ($res === false) {
            return json([
                'code' => 500,
                'msg' => 'update failed'
            ]);
        }
        return json([
            'code' => 200,
            'msg' => 'success'
        ]);
    }


    /**
     * 根据type_id返回书本信息列表(0表示返回全部,其余则根据type_id返回)
     *
     * @param String $type_id 
     * @return json 书本数组
     */
    public function bookList($type_id = 0)
    {
        $book = new ViewBookList();
        $list = [];
        if ($type_id == '0') {
            $list = $book->select();
        } else {
            $list = $book->where('book_type_id', $type_id)->select();
        }

        return json([
            'code' => '200',
            'data' => $list
        ]);
    }

    /**
     *根据书本id返回某本书信息
     *
     * @param String $book_id 
     * @return json 书籍信息数组
     */
    public function book($book_id)
    {
        if (is_null($book_id)) {
            return json([
                'code' => 404,
                'msg' => 'Necessary param is null'
            ]);
        }
        $book = new ViewBookDetail();
        $book = $book->where('book_id', $book_id)->find();
        return json([
            'code' => '200',
            'data' => $book
        ]);
    }


    /**
     * 返回书本类型
     *
     * @param  
     * @return 书本类型数组
     */
    public function bookType()
    {
        $bookTypeList = Config::load('config.php')['book_type'];
        return json([
            'code' => '200',
            'data' => $bookTypeList
        ]);
    }


    /**
     * 根据用户id返回购物车列表
     * 
     * @param String $user_id 
     * @return 购物车数组
     */
    public function shopCart($user_id)
    {
        if (is_null($user_id)) {
            return json([
                'code' => '404',
                'msg' => 'Necessary param is null'
            ]);
        }
        $cart = new ViewShopCart();
        $list = $cart->where('user_id', $user_id)->select();
        return json([
            'code' => '200',
            'data' => $list
        ]);
    }


    /**
     * 根据书本id返回对应评价
     *
     * @param String $book_id
     * @return json
     */
    public function evaluateList($book_id)
    {
        if (is_null($book_id)) {
            return json([
                'code' => '404',
                'msg' => 'book_id is null'
            ]);
        }
        $evaluate = new ViewBookEvaluate();
        $evaluateList = $evaluate->where('book_id', $book_id)->select();
        // 返回结果
        $data = [];
        // 对评价的评论的顺组
        $comment = [];
        //不能直接等于evaluateList[0]['order_item_id'],否则若数组为空则报错
        $prev = -1;
        foreach ($evaluateList as $evaluateItem) {
            $temp = [
                'id' => $evaluateItem['evaluate_id'],
                'name' => $evaluateItem['nick_name'],
                'content' => $evaluateItem['content'],
                'time' => $evaluateItem['evaluate_time'],
                'order_item_id' => $evaluateItem['order_item_id'],
                'like_count' => EvaluateLike::where('evaluate_id', $evaluateItem['evaluate_id'])->count()
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
            'id' => $comment[0]['id'],
            'name' => $comment[0]['name'],
            'content' => $comment[0]['content'],
            'time' => $comment[0]['time'],
            'order_item_id' => $comment[0]['order_item_id'],
            'like_count' => $comment[0]['like_count'],
            'comment' => array_splice($comment, 1),
        ];
    }



    /**
     * 根据请求创建订单(待支付)
     *
     * @param String $request
     * @return 购买信息
     */
    public function createOrder(Request $request)
    {
        $orderList = $request->param()['order_list'];           //接收到的订单数组
        $user_id = $request->param()['user_id'];                //用户id
        $address_id = $request->param()['address_id'];          //地址id
        $remark = $request->param()['remark'];                  //订单备注

        //创建订单，此时订单状态为待支付
        $order = new Order([
            'user_id'           => $user_id,
            'address_id'        => $address_id,
            'order_state_id'    => 0,
            'create_time'       => date("Y-m-d H:i:s"),
            //用户是否删除订单的标志，0表示用户在查看订单列表时不删除订单
            'flag'              => 0,
            'remark'            => $remark,
        ]);
        $result = $order->save();
        if ($result === false) {
            return json([
                'code'  => 500,
                'msg'   => 'insert failed'
            ]);
        }
        $order_id = $order->order_id;
        foreach ($orderList as $item) {
            try {
                $this->createOrderItem($item, $order_id);
            } catch (Exception $th) {
                return json([
                    'code'  => $th->getCode(),
                    'msg'   => $th->getMessage()
                ]);
            }
        }
        return json([
            "statusCode"    => 200,
            "msg"           => "创建订单成功",
            "order_id"      =>  $order_id                //返回订单id
        ]);
    }

    /**
     * 创建订单项
     *
     * @param Array $item
     * @param String $order_id
     * @return void
     */
    protected function createOrderItem($item, $order_id)
    {
        //给订单添加物品
        $book = new Book();       //先要查此时的书本库存数量够不够，不够则返回库存不足
        $book = $book->where('book_id', $item['book_id'])->find();
        $count = $book->book_count - $item['count'];

        if ($count < 0) {
            throw new Exception($book->book_name . '库存不足', 401);
        }
        $book->book_count = $count; //更改库存数量
        $result = $book->save();

        if ($result === false) {
            throw new Exception('update failed', 500);
        }
        //创建订单里的物品信息
        $orderItem = new OrderItem([
            'count'     => $item['count'],
            'book_id'   => $item['book_id'],
            'price'     => $item['price'],
            'order_id'  => $order_id,
        ]);

        $result = $orderItem->save();

        if ($result === false) {
            throw new Exception('insert failed', 500);
        }
    }



    /**
     * 修改订单地址信息(要在发货前修改)
     *
     * @param Request $request
     * @return void
     */
    public function orderUpdate(Request $request)
    {
        $order_id = $request->param()['order_id'];                   //订单id
        $address_id = $request->param()['address_id'];         //用户修改的地址id

        if (is_null($address_id)) {
            return json([
                'code'  => '404',
                'msg'   => 'Necessary param is null'
            ]);
        }
        $order = new Order();
        $order = $order->where('order_id', $order_id)->find();

        if (empty($order))

            return json([
                'code'  => '401',
                'msg'   => '订单不存在'
            ]);

        if ($order->order_state_id != 1)
            return json([
                'code'  => '401',
                'msg'   => '订单不是待发货状态，无法修改'
            ]);

        $order->address_id = $address_id;

        $result = $order->save();
        if ($result === false) {
            return json([
                'code'  => 500,
                'msg'   => 'update failed'
            ]);
        }
        return json([
            "statusCode"    => 200,
            "msg"           => "修改订单成功",
        ]);
    }


    /**
     * 修改订单状态（用户确认收货，商家发货）
     *
     * @param Request $request
     * @return void
     */
    public function orderStateUpdate(Request $request)
    {
        $order_id = $request->param()['order_id'];
        $order_state_id = $request->param()['order_state_id'];
        if (is_null($order_id) || is_null($order_state_id)) {
            return json([
                'code'  => '404',
                'msg'   => 'Necessary param is null'
            ]);
        }
        $order = new Order();
        $order = $order->where('order_id', $order_id)->find();

        if (is_null($order))

            return json([
                'code'  => '401',
                'msg'   => '订单不存在'
            ]);

        $order->order_state_id = $order_state_id;
        $result = $order->save();
        if ($result === false) {
            return json([
                'code'  => 500,
                'msg'   => 'update failed'
            ]);
        }
        return json([
            "statusCode"    => 200,
            "msg"           => "修改订单状态成功",
        ]);
    }




    /**
     * 用户查看订单列表删除订单
     *
     * @param Request $request
     * @return void
     */
    public function orderDelete(Request $request)
    {
        $order_id = $request->param()['order_id'];   //用户删除的订单id
        if (is_null($order_id)) {
            return json([
                'code'  => '404',
                'msg'   => 'Necessary param is null'
            ]);
        }
        $order = new Order();
        $order = $order->where('order_id', $order_id)->find();

        if (empty($order)) {
            return json([
                'code'  => '401',
                'msg'   => '订单不存在'
            ]);
        }

        $order->flag = 1;           //1表示用户删除订单，商家仍然可看见订单
        $result = $order->save();
        if ($result === false) {
            return json([
                'code'  => 500,
                'msg'   => 'update failed'
            ]);
        }

        return json([
            "statusCode"    => 200,
            "msg"           => "修改订单状态成功",
        ]);
    }

    /**
     * 用户取消订单
     *
     * @param Request $request
     * @return void
     */
    public function orderCancel(Request $request)
    {
        $order_id = $request->param()['order_id'];  //订单id
        $detail = $request->param()['detail'];  //订单id

        if (is_null($order_id)) {
            return json([
                'code'  => '404',
                'msg'   => 'Necessary param is null'
            ]);
        }
        $order = new Order();
        $order = $order->where('order_id', $order_id)->find();

        if (empty($order)) {
            return json([
                'code'  => '401',
                'msg'   => '订单不存在'
            ]);
        }
        Db::startTrans();
        try {
            $order->order_state_id = 5; //订单状态id改成取消订单id
            $result = $order->save();
            if ($result === false) {
                Db::rollback();
            }
            $orderItem = new OrderItem();
            $orderList = $orderItem->where('order_id', $order_id)->select();
            foreach ($orderList as $item) {
                $this->updateItemBook($item);
            }
            Db::commit();

            $OrderCancel = new OrderCancel();
            $OrderCancel->save([
                'detail'    => $detail,
                'order_id'  => $order_id
            ]);
        } catch (Exception $th) {
            Db::rollback();
            return json([
                'code'  => 500,
                'msg'   => 'failed'
            ]);
        }
        return json([
            'code'  => 200,
            'msg'   => '取消成功'
        ]);
    }


    //mark

    protected function updateItemBook($item)
    {
        $book = new Book();
        $book = $book->where('book_id', $item['book_id'])->find();
        $count = $book->book_count + $item['count'];    //加回订单的数量
        $book->book_count = $count;     //更改库存数量
        $result = $book->save();

        if ($result === false) {
            throw new Exception("update failed", 500);
        }
    }

    /**
     * 添加书本到购物车
     *
     * @param Request $request
     * @return void
     */
    public function cartAdd(Request $request)
    {
        $book_id = $request->param()['book_id'];   //用户收藏的书本id
        $user_id = $request->param()['user_id'];   //用户id


        if (is_null($user_id) || is_null($book_id))

            return json([
                'code'  => '401',
                'msg'   => '请求参数有误'
            ]);

        $shopCart = new ShopCart();

        $oldCart =  $shopCart       //首先查询购物车是否有该物品               
            ->where('user_id', $user_id)
            ->where('book_id', $book_id)
            ->find();

        //如果不是空的，则存在购物车的该物品数量加1
        if (!is_null($oldCart)) {
            $oldCart->count++;

            $result = $oldCart->save();
        }
        //如果是空的，则直接添加到购物车
        else {
            $cart = new ShopCart([
                'user_id'       => $user_id,
                'book_id'       => $book_id,
                'create_time'   => date("Y-m-d H:i:s"),
                'count'         => 1
            ]);

            $result = $cart->save();
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
        $bookList = $request->param()['book_list'];   //用户选择的所有书本
        $user_id = $request->param()['user_id'];   //用户id
        $ifAll = $request->param()['if_all'];   //是否全部删除

        $shopCart = new ShopCart();
        $oldCart = $shopCart->where('user_id', $user_id);


        if ($ifAll == '1') {

            $result = $oldCart->delete();

            if ($result === false) {
                return json([
                    'code'  => 500,
                    'msg'   => 'delete failed'
                ]);
            } else {
                return json([
                    "statusCode"    => 200,
                    "msg"           => "删除成功",
                ]);
            }
        }


        if (is_null($user_id) || is_null($bookList))

            return json([
                'code'  => '401',
                'msg'   => '请求参数有误'
            ]);

        //遍历书本数组
        foreach ($bookList as $item) {
            //查找购物车的书本
            $oldItem = $oldCart->where('book_id', $item['book_id'])->find();

            //如果不是空的，则删除
            if (!is_null($oldItem)) {

                $result = $oldItem->delete();

                if ($result === false) {
                    return json([
                        'code'  => 500,
                        'msg'   => 'delete failed'
                    ]);
                }
            }
            //如果是空的，提示不存在
            else {
                return json([
                    'code'  => 401,
                    'msg'   => '不存在该物品'
                ]);
            }
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
        $book_id = $request->param()['book_id'];   //用户收藏的书本id
        $user_id = $request->param()['user_id'];   //用户id
        $count = $request->param()['count'];   //数量


        if (is_null($user_id) || is_null($user_id))

            return json([
                'code'  => '401',
                'msg'   => '请求参数有误'
            ]);

        $shopCart = new ShopCart();

        $oldCart =  $shopCart       //首先查询购物车是否有该物品               
            ->where('user_id', $user_id)
            ->where('book_id', $book_id)
            ->find();

        //如果不是空的，则添加数量
        if (!is_null($oldCart)) {
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
        //如果是空的
        else {
            return json([
                'code'  => 401,
                'msg'   => '不存在该物品'
            ]);
        }
    }

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

        $oldCollect =  $collect       //首先查询购物车是否有该物品               
            ->where('user_id', $user_id)
            ->where('book_id', $book_id)
            ->find();

        //如果不是空的，则存在购物车的该物品数量加1
        if (!is_null($oldCollect)) {
            return json([
                'code'  => 401,
                'msg'   => '已收藏过'
            ]);
        }
        //如果是空的，则直接添加到购物车
        else {
            $collect = new Collect([
                'user_id'       => $user_id,
                'book_id'       => $book_id,
                'create_time'   => date("Y-m-d H:i:s"),
            ]);

            $result = $collect->save();
        }

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

    /**
     * 删除收藏夹的物品
     *
     * @param Request $request
     * @return void
     */
    public function collectDelete(Request $request)
    {
        $bookList = $request->param()['book_list'];   //用户选择的所有书本
        $user_id = $request->param()['user_id'];   //用户id
        $ifAll = $request->param()['if_all'];   //是否全部删除

        $collect = new Collect();
        $oldCollect = $collect->where('user_id', $user_id);

        if ($ifAll == '1') {

            $result = $oldCollect->delete();

            if ($result === false) {
                return json([
                    'code'  => 500,
                    'msg'   => 'delete failed'
                ]);
            } else {
                return json([
                    "statusCode"    => 200,
                    "msg"           => "删除成功",
                ]);
            }
        }


        if (is_null($user_id) || is_null($bookList))

            return json([
                'code'  => '401',
                'msg'   => '请求参数有误'
            ]);

        //遍历书本数组
        foreach ($bookList as $item) {
            //查找收藏夹的书本
            $oldItem = $oldCollect->where('book_id', $item['book_id'])->find();

            //如果不是空的，则删除
            if (!is_null($oldItem)) {

                $result = $oldItem->delete();

                if ($result === false) {
                    return json([
                        'code'  => 500,
                        'msg'   => 'delete failed'
                    ]);
                }
            }
            //如果是空的，提示不存在
            else {
                return json([
                    'code'  => 401,
                    'msg'   => '不存在该物品'
                ]);
            }
        }

        return json([
            "statusCode"    => 200,
            "msg"           => "删除成功",
        ]);
    }
}
