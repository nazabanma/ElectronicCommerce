<?php

namespace app\api\model;

use think\Model;
use app\api\model\Collect;
use app\api\model\OrderItem;

class ViewBookDetail extends Model
{
    /**
     *根据书本id返回某本书信息
     *
     * @param String $book_id 
     * @return json 书籍信息数组
     */
    public function bookDetail($book_id,$user_id)
    {
        if (is_null($book_id)) {
            return json([
                'code' => 404,
                'msg' => 'Necessary param is null'
            ]);
        }
        $book = new ViewBookDetail();
        $book = $book->where('book_id', $book_id)->find();
        //判断是否收藏过该书
        $book['ifCollect']= empty(Collect::where('book_id', $book_id)->where('user_id', $user_id)->find())?0:1;
        $book['sell_count']=OrderItem::where('book_id',$book_id)->count();  //销量
        return json([
            'code' => '200',
            'data' => $book
        ]);
    }
}
