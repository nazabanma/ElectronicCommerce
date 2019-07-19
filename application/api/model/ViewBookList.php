<?php

namespace app\api\model;
use think\Request;
use think\Model;

class ViewBookList extends Model
{
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
     * 根据书名进行模糊查询
     *
     * @param Request $request
     * @return json 书本数组
     */
    public function bookFind($name)
    {

        $book = new ViewBookList();
        $list = [];
        if (is_null($name)) {
            return json([
                'code' => '404',
                'msg' => 'Necessary param is null'
            ]);
        } else {
            $list = $book->where('book_name','like','%'.$name.'%')->select();
        }
       
        return json([
            'code' => '200',
            'data' => $list
        ]);
    }
}
