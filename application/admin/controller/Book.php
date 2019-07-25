<?php

namespace app\admin\controller;

use app\admin\common\base\BaseController;
use app\admin\model\ViewBookDetail;
use think\Request;

class Book extends BaseController
{
    private $Book;
    private $ViewBookDetail;

    function _initialize()
    {
        $this->Book = new \app\admin\model\Book();
        $this->ViewBookDetail = new ViewBookDetail();
    }

    /**
     * 书本列表
     *
     * @param String $page
     * @return json
     */
    public function bookList($page)
    {
        return $this->ViewBookDetail->bookList($page);
    }

    /**
     * 模糊查询书本
     *
     * @param Request $request
     * @return json
     */
    public function findBookFuzzy(Request $request)
    {
        return $this->Book->findBookFuzzy($request);
    }

    /**
     * 书本详情
     *
     * @param String $book_id
     * @return json
     */
    public function bookDetail($book_id)
    {
        return $this->ViewBookDetail->bookDetail($book_id);
    }

    /**
     * 删除书本
     *
     * @param String $book_id
     * @return json
     */
    public function delBook($book_id)
    {
        return $this->Book->delBook($book_id);
    }

    /**
     * 更新书本信息
     *
     * @param Request $request
     * @return json
     */
    public function updateBook(Request $request)
    {
        return $this->Book->updateBook($request);
    }

    /**
     * 上传图片
     *
     * @param Request $request
     * @return json
     */
    public function upload(Request $request)
    {
        return $this->Book->upload($request);
    }

    /**
     * 添加书本
     *
     * @param Request $request
     * @return String
     */
    public function addBook(Request $request)
    {
        return $this->Book->addBook($request);
    }
}
