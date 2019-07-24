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

    public function bookList($page)
    {
        return $this->ViewBookDetail->bookList($page);
    }

    public function findBookFuzzy(Request $request)
    {
        return $this->Book->findBookFuzzy($request);
    }

    public function bookDetail($book_id)
    {
        return $this->ViewBookDetail->bookDetail($book_id);
    }

    public function delBook($book_id)
    {
        return $this->Book->delBook($book_id);
    }

    public function updateBook(Request $request)
    {
        return $this->Book->updateBook($request);
    }

    public function upload(Request $request)
    {
        return $this->Book->upload($request);
    }
}
