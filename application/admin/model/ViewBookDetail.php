<?php

namespace app\admin\model;

use think\Model;

class ViewBookDetail extends Model
{

    /**
     * 书本详情
     *
     * @param String $book_id
     * @return json
     */
    public function bookDetail($book_id)
    {
        $model = new ViewBookDetail();
        return json([
            'code'  => 200,
            'data'  => $model->where("book_id", $book_id)->find(),
        ]);
    }

    /**
     * 书本列表
     *
     * @param String $page
     * @return json
     */
    public function bookList($page)
    {
        $model = new ViewBookDetail();
        return json([
            'code'  => 200,
            'data'  => $model->limit(($page - 1) * 10, 10)->select(),
        ]);
    }
}
