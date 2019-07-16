<?php

namespace app\api\controller;

use think\Controller;
use think\Request;
use app\api\model\Address;
use app\api\model\ViewMyCollect;
use app\api\model\ViewMyOrder;
use app\api\model\ViewBookList;
use app\api\model\ViewBookDetail;
use app\api\model\ViewBookEvaluate;
use app\api\model\ViewMyAddress;
use app\api\model\ViewShopCart;

class User extends Controller
{


    public function login(Request $request)
    {
        $username = $request->param('username');
        $password = $request->param('password');

        if (empty($password) || empty($username)) {
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
     * @return json
     */
    public function myCollect($user_id)
    {
        $Collect = new ViewMyCollect();
        $data = $Collect->where('user_id', $user_id)->select();
        return json([
            'code' => '200',
            'data' => $data
        ]);
    }

    /**
     * 查询所有订单
     *
     * @param String $user_id
     * @return json
     */
    public function myOrder($user_id)
    {
        $Order = new ViewMyOrder();
        $data = $Order->where('user_id', $user_id)->select();
        return json([
            'code' => '200',
            'data' => $data
        ]);
    }

    /**
     * 查询所有地址
     *
     * @param String $user_id
     * @return json
     */
    public function myAddress($user_id)
    {
        $Address = new ViewMyAddress();
        $data = $Address->where('user_id', $user_id)->select();
        return json([
            'code' => '200',
            'data' => $data
        ]);
    }

    public function editAddress(Request $request)
    {
        $address_id = $request->param('address_id');
        if (empty($address_id)) {
            return json([
                'code' => 404,
                'msg' => 'id为空'
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
        if ($res == 0) {
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
     * 根据type_id返回书本信息列表(0表示返回全部,其余则根据type_id返回)K
     *
     * @param [type] $type_id 
     * @return 书本数组
     */
    public function book_list($type_id)
    {
        $book = new ViewBookList();
        $list = '';
        if ($type_id == '0') {
            $list = $book->select();
        } else {
            $list = $book->where('book_type_id', $type_id)->select();
        }

        return json($list);
    }

    /**
     *根据书本id返回某本书信息
     *
     * @param [type] $book_id 
     * @return 某本书数组
     */
    public function book($book_id)
    {

        $book = new ViewBookDetail();
        $book = $book->where('book_id', $book_id)->select();
        return json($book);
    }


    /**
     * 返回书本类型
     *
     * @param  
     * @return 书本类型数组
     */
    public function book_type()
    {

        $book_type_list = BookType::all();

        return json($book_type_list);
    }


    /**
     * 根据用户id返回购物车列表
     * 
     * @param [type] $user_id 
     * @return 购物车数组
     */
    public function shop_cart($user_id)
    {
        $cart = new ViewShopCart();

        $list = $cart->where('user_id', $user_id)->select();

        return json($list);
    }



    /**
     * 根据书本id返回对应评价
     *
     * @param Sting $book_id
     * @return json
     */
    public function evaluate_list($book_id)
    {

        $evaluate = new ViewBookEvaluate();
        $list = $evaluate->where('book_id', $book_id)->select();

        $evaluate_list = [];     //评价数组
        $evaluate_item = [];     //评价数组的每个元素数组
        $comment_list = [];     //评价里的评论数组，作为评价数组的元素
        $comment_item = [];    //评论数组的每个元素数组
        $count = 0;             //用于判断是否是评价

        $evaluate_item['id'] = $list[0]['evaluate_id'];          //先给评价数组的第一个元素赋值
        $evaluate_item['name'] = $list[0]['nick_name'];
        $evaluate_item['content'] = $list[0]['content'];
        $evaluate_item['time'] = $list[0]['evaluate_time'];
        $evaluate_item['order_item_id'] = $list[0]['order_item_id'];
        $evaluate_item['like_count'] = EvaluateLike::where('evaluate_id', $list[0]['evaluate_id'])->count();

        for ($i = 1; $i < count($list); $i++) {


            if ($i + 1 == count($list) || $list[$i + 1]['order_item_id'] != $list[$i]['order_item_id']) {  //如果下一条是新的评价，则要向评价数组添加最新的评价元素

                if ($list[$i - 1]['order_item_id'] == $list[$i]['order_item_id']) {    //判断该条是评价还是评论
                    $comment_item['name'] = $list[$i]['nick_name'];
                    $comment_item['content'] = $list[$i]['content'];
                    $comment_item['time'] = $list[$i]['evaluate_time'];
                    $comment_item['order_item_id'] = $list[$i]['order_item_id'];
                    array_push($comment_list, $comment_item);
                } else {
                    $evaluate_item['id'] = $list[$i]['evaluate_id'];
                    $evaluate_item['name'] = $list[$i]['nick_name'];
                    $evaluate_item['content'] = $list[$i]['content'];
                    $evaluate_item['time'] = $list[$i]['evaluate_time'];
                    $evaluate_item['order_item_id'] = $list[$i]['order_item_id'];
                    $evaluate_item['like_count'] = EvaluateLike::where('evaluate_id', $list[$i]['evaluate_id'])->count();
                }
                $evaluate_item['comment'] = $comment_list;       //评价数组的元素中的评论数组
                array_push($evaluate_list, $evaluate_item);    //向评价数组添加最新的评价元素
                $comment_list = [];

                $count = 1;
            } else {

                if ($count == 1) {                                          //如果是评价
                    $evaluate_item['id'] = $list[$i]['evaluate_id'];
                    $evaluate_item['name'] = $list[$i]['nick_name'];
                    $evaluate_item['content'] = $list[$i]['content'];
                    $evaluate_item['time'] = $list[$i]['evaluate_time'];
                    $evaluate_item['order_item_id'] = $list[$i]['order_item_id'];
                    $evaluate_item['like_count'] = EvaluateLike::where('evaluate_id', $list[$i]['evaluate_id'])->count();
                    $count++;
                } else {                                          //如果是评论

                    $comment_item['name'] = $list[$i]['nick_name'];
                    $comment_item['content'] = $list[$i]['content'];
                    $comment_item['time'] = $list[$i]['evaluate_time'];
                    $comment_item['order_item_id'] = $list[$i]['order_item_id'];
                    array_push($comment_list, $comment_item);
                }
            }
        }


        return json($evaluate_list);
        // $list = Evaluate::hasWhere('OrderItem', ['book_id' => $book_id])->with('order_item')->select();
        // return json($list);
    }

    /**
     * 根据请求创建订单
     *
     * @param [type] $request
     * @return 购买信息
     */

    public function buy(Request $request)
    {
        $order_list = $request->param()['order_list'];
        $user_id = $request->param()['user_id'];
        $address_id = $request->param()['address_id'];


        $order = new Order();
        $order->user_id = $user_id;
        $order->address_id = $address_id;
        $order->create_time = now();
        $order->save();
        $order_id = $order->id;

        foreach ($order_list as $order_item) {
            $order_item = new OrderItem();

            $order_item->gid = $order_item['item_id'];
            $order_item->count = $order_item['count'];
            $order_item->book_id = $order_item['book_id'];
            $order_item->order_id = $order_id;
            $order_item->save();
        }

        return json([
            "statusCode" => 200,
            "msg" => "购买成功",
            "order_id" =>  $order_id
        ]);
    }
}
