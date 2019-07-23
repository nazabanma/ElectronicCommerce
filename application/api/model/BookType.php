<?php
namespace app\api\model;

use think\Model;

class BookType extends Model
{

 /**
     * 返回书本类型
     *
     * @param  
     * @return 书本类型数组
     */
    public function bookType()
    {
        $bookTypeList = BookType::all();
        return json([
            'code' => '200',
            'data' => $bookTypeList
        ]);
    }


 }

?>
            