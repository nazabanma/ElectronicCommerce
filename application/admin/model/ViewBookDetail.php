<?php

namespace app\admin\model;

use think\Model;

class ViewBookDetail extends Model
{

    public function bookDetail($book_id)
    {
        $model = new ViewBookDetail();
        return json([
            'code'  => 200,
            'data'  => $model->where("book_id", $book_id)->find(),
        ]);
    }

    public function bookList($page)
    {
        $model = new ViewBookDetail();
        return json([
            'code'  => 200,
            'data'  => $model->limit(($page - 1) * 10, 10)->select(),
        ]);
    }
}
