<?php

namespace app\api\controller;

use think\Controller;
use think\Request;
use think\Config;
use app\api\model\Address;
use app\api\model\Order;
use app\api\model\Collect;
use app\api\model\ShopCart;
use app\api\model\ViewMyCollect;
use app\api\model\ViewMyOrder;
use app\api\model\ViewBookList;
use app\api\model\ViewBookDetail;
use app\api\model\ViewBookEvaluate;
use app\api\model\ViewMyAddress;
use app\api\model\ViewShopCart;



class User extends Controller
{

    private $Order;
    private $Cart;
    private $Collect;
    function _initialize()
    {
        parent::_initialize();
        $this->Order = new Order();
        $this->Cart = new ShopCart();
        $this->Collect = new Collect();
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
    public function orderCreate(\think\Request $request)
    {
        return $this->Order->orderCreate($request);
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
        $this->Cart->cartAdd();
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
        $this->Cart->cartUpdate($request);
    }

    /**
     * 添加书本到收藏夹
     *
     * @param Request $request
     * @return void
     */
    public function collectAdd(Request $request)
    {
        $this->Collect->collectAdd($request);
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
}
