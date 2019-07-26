<?php

namespace app\api\controller;

use think\Controller;
use think\Config;
use app\api\model\ViewBookList;
use app\api\model\ViewBookDetail;
use app\api\model\ViewBookEvaluate;
use app\api\model\BookType;

class Book extends Controller
{

    private $ViewBookList;
    private $Book;

    function _initialize()
    {
        $this->ViewBookList = new ViewBookList();
    }
    /**
     * 根据type_id返回书本信息列表(0表示返回全部,其余则根据type_id返回)
     *
     * @param String $type_id 
     * @return json 书本数组
     */
    public function bookList($type_id = 0)
    {
        return $this->ViewBookList->bookList($type_id);
    }

    /**
     * 根据页码查询
     *
     * @param Request $request
     * @return json 书本数组
     */
    public function bookListByPage($page, $type_id = 0)
    {
        return $this->ViewBookList->bookListByPage($page, $type_id);
    }

    /**
     *根据书本id返回某本书信息
     *
     * @param String $book_id 
     * @return json 书籍信息数组
     */
    public function bookDetail($book_id, $user_id)
    {
        $model = new ViewBookDetail();
        return $model->bookDetail($book_id, $user_id);
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
        return $this->ViewBookList->bookFind($name);
    }
}
