<?php

namespace app\api\model;

use think\Model;

class ViewBookDetail extends Model
{
    /**
     *根据书本id返回某本书信息
     *
     * @param String $book_id 
     * @return json 书籍信息数组
     */
    public function bookDetail($book_id)
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
}
