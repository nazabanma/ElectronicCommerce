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

    private $Order;

    function _initialize()
    {
        parent::_initialize();
        $this->Order = new Order();
    }
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
        $model = new ViewMyAddress();
        return $model->myAddress($user_id);
    }

    /**
     * 编辑收货地址
     *
     * @param Request $request
     * @return json 编辑结果
     */
    public function editAddress(\think\Request $request)
    {
        return  Address::editAddress($request);
    }


    /**
     * 根据type_id返回书本信息列表(0表示返回全部,其余则根据type_id返回)
     *
     * @param String $type_id 
     * @return json 书本数组
     */
    public function bookList($type_id = 0)
    {
        $model = new ViewBookList();
        return $model->bookList($type_id);
    }

    /**
     *根据书本id返回某本书信息
     *
     * @param String $book_id 
     * @return json 书籍信息数组
     */
    public function bookDetail($book_id)
    {
        $model = new ViewBookDetail();
        return $model->bookDetail($book_id);
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
        $model = new ViewShopCart();
        return $model->shopCart($user_id);
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



    /**
     * 根据请求创建订单(待支付)
     *
     * @param String $request
     * @return 购买信息
     */
    public function createOrder(\think\Request $request)
    {
        return $this->Order->createOrder($request);
    }


    /**
     * 修改订单地址信息(要在发货前修改)
     *
     * @param Request $request
     * @return void
     */
    public function orderUpdate(\think\Request $request)
    {
        return $this->Order->orderUpdate($request);
    }


    /**
     * 修改订单状态（用户确认收货，商家发货）
     *
     * @param Request $request
     * @return void
     */
    public function orderStateUpdate(Request $request)
    {
        $this->Order->orderStateUpdate($request);
    }




    /**
     * 用户查看订单列表删除订单
     *
     * @param Request $request
     * @return void
     */
    public function orderDelete(Request $request)
    {
        return $this->orderDelete($request);
    }

    /**
     * 用户取消订单
     *
     * @param Request $request
     * @return void
     */
    public function orderCancel(Request $request)
    {
        return $this->Order->orderCancel($request);
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
