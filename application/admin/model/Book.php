<?php

namespace app\admin\model;

use think\Model;
use think\Request;
use think\Config;

class Book extends Model
{

    /**
     * 模糊查询书本
     *
     * @param Request $request
     * @return json
     */
    public function findBookFuzzy(Request $request)
    {
        $book_id = $request->param('book_id');
        $book_name = $request->param('book_name');
        $type = $request->param('book_type');
        $author = $request->param('book_author');
        $Book = new  ViewBookDetail();
        $data = $Book->where('book_id', 'like', '%' . $book_id . '%')
            ->where('book_name', 'like', '%' . $book_name . '%')
            ->where('type_name', 'like', '%' . $type . '%')
            ->where('book_author', 'like', '%' . $author . '%')
            ->select();
        return json([
            'code'  => 200,
            'data'  => $data,
        ]);
    }

    /**
     * 删除书本
     *
     * @param String $book_id
     * @return json
     */
    public function delBook($book_id)
    {
        $res = Book::destroy($book_id);
        if ($res == 1) {
            return json([
                'code'  => '200',
                'msg'   => 'success'
            ]);
        }
        return json([
            'code'  => '500',
            'msg'   => 'delete failed',
        ]);
    }

    /**
     * 更新书本信息
     *
     * @param Request $request
     * @return json
     */
    public function updateBook(Request $request)
    {
        $data = $request->param();
        $data['book_type_id'] = Config::load('config.php')['book_type_to_id'][$data['type_name']];
        unset($data['type_name']);
        $Book = Book::get($data['book_id']);
        $res = $Book->save($data);
        if ($res === false) {
            return json([
                'code'  => 500,
                'msg'   => 'update failed'
            ]);
        }
        return json([
            'code'  => 200,
            'msg'   => 'success'
        ]);
    }

    /**
     * 上传图片
     *
     * @param Request $request
     * @return json
     */
    public function upload(Request $request)
    {
        $file = request()->file('img');
        $baseUrl = ROOT_PATH . 'public' . DS . 'uploads';
        $imgDomain = 'http://admin.wyxs.talesrunner.org/uploads/';
        // 移动到框架应用根目录/public/uploads/ 目录下
        if ($file) {
            $info = $file->move($baseUrl);
            if ($info) {
                // 成功上传后 获取上传信息
                return json([
                    'code'  => 200,
                    'data'   => $imgDomain . $info->getSaveName()
                ]);
            } else {
                return json([
                    'code'  => 500,
                    'msg'   => $file->getError()
                ]);
            }
        }
    }

    /**
     * 添加书本
     *
     * @param Request $request
     * @return String
     */
    public function addBook(Request $request)
    {
        $data = $request->param();
        $Book = new Book();
        $res = $Book->save($data);
        if ($res == 1) {
            return json([
                'code'  => 200,
                'msg'   => 'success'
            ]);
        }
        return json([
            'code'  => 500,
            'msg'   => 'failed'
        ]);
    }
}
