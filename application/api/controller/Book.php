<?php

namespace app\api\controller;

use think\Controller;
use think\Config;
use app\api\model\ViewBookList;
use app\api\model\ViewBookDetail;
use app\api\model\ViewBookEvaluate;

class Book extends Controller
{

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
     * 根据书本名字返回搜索结果
     *
     * @param String $book_id
     * @return json
     */
    public function bookFind($name)
    {
        $model = new ViewBookList();
        return $model->bookFind($name);
    }
    
    /**
     * 添加书本
     *
     * @param Request $request
     * @return json 添加结果
     */
    public function bookAdd(Request $request)
    {
        $model = new Book();
        return $model->bookAdd($request);
    }

     /**
     * 编辑书本
     *
     * @param Request $request
     * @return json 编辑结果
     */
    public function bookEdit(Request $request)
    {
        $model = new Book();
        return $model->bookEdit($request);
    }

    /**
     * 删除书本
     *
     * @param String $book_id
     * @return json 删除结果
     */
    public function bookDelete($book_id)
    {
        $model = new Book();
        return $model->bookDelete($book_id);
    }

    



}
