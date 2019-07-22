<?php

namespace app\api\model;

use think\Model;

class Book extends Model
{

    

    public function collect()
    {
        return $this->hasMany("Collect", "book_id");
    }


   public function bookType()
   {
       return json(Config::get('book_type'));
   }


    /**
     * 添加书本
     *
     * @param Request $request
     * @return json 添加结果
     */
    public function bookAdd(Request $request)
    {
        $book=new Book();
        $res = $book->save($request->param());
        if ($res === false) {
            return json([
                'code' => 500,
                'msg' => 'insert failed'
            ]);
        }
        return json([
            'code' => 200,
            'msg' => 'success'
        ]);
    }

    /**
     * 编辑书本
     *
     * @param Request $request
     * @return json 编辑结果
     */
    public  function bookEdit(Request $request)
    {
        $book_id = $request->param('book_id');
        if (is_null($book_id)) {
            return json([
                'code' => 404,
                'msg' => 'Necessary param is null'
            ]);
        }
        $book = Book::get($book_id);
        if (!$book) {
            return json([
                'code' => 404,
                'msg' => '该记录不存在'
            ]);
        }
        //通过两次键值翻转,去除数组中的索引部分,否则更新数据时会将索引识别为字段
        $data = array_flip(array_flip($request->param()));
        $res = $book->save($data);
        if ($res === false) {
            return json([
                'code' => 500,
                'msg' => 'update failed'
            ]);
        }
        return json([
            'code' => 200,
            'msg' => 'success'
        ]);
    }


    /**
     * 删除书本
     *
     * @param String $book_id
     * @return json 删除结果
     */
    public function bookDelete($book_id)
    {
        if (is_null($book_id)) {
            return json([
                'code' => 404,
                'msg' => 'Necessary param is null'
            ]);
        }

        $res=Book::destroy($book_id);
        
        if ($res ===1) {
            return json([
                'code' => 200,
                'msg' => '删除成功'
            ]);
            
        }
        return json([
            'code' => 500,
            'msg' => 'delete failed'
        ]);


    }


}
