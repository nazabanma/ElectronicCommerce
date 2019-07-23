<?php

namespace app\admin\model;

use think\Model;
use think\Request;
use think\Config;

class Book extends Model
{



    public function upload()
    {
        session_start();
        include 'moodFile.php';
        header('Content-Type:application/json; charset=utf-8');
        $mood_id = $_POST[mood_id];
        $user_id = $_POST[user_id];
        $mood_body = $_POST[mood_body];
        $mood_date = $_POST[mood_date];
        $sendDate = $_POST[sendDate];
        $i = 0;
        $savepath = '/' . $user_id . '/';
        if (!empty($_FILES['mood_pic'])) //使用数组判断当前是否上传了图片
        {
            foreach ($_FILES['mood_pic']['name'] as $key => $value) {
                if (
                    ($_FILES["mood_pic"]["type"][$key] == "image/gif")
                    || ($_FILES["mood_pic"]["type"][$key] == "image/jpeg")
                    || ($_FILES["mood_pic"]["type"][$key] == "image/jpg")
                    || ($_FILES["mood_pic"]["type"][$key] == "image/png")
                ) {
                    $imgType = explode('.', $_FILES['mood_pic']['name'][$key])[1];
                    //设置照片的存放相对路径和命名。命名照片例：20161226_2.png，下划线后跟遍历的键值区分照片，可在此处自行设置规则！！
                    $imgName = md5($user_id . $sendDate . '-' . $i) . '.' . $imgType;
                    //$imgName=$_FILES['mood_pic']['name'][$key];
                    //将上传的文件移动到新位置
                    move_uploaded_file($_FILES['mood_pic']['tmp_name'][$key], $savepath . $imgName);
                    $source =  $savepath . $imgName; //原图文件名
                    $dst_img = $imgName; //保存图片的文件名
                    $percent = 0.5;  #原图压缩，不缩放，但体积大大降低
                    $image = (new imgcompress($source, $percent))->compressImg($dst_img, $savepath);
                    //显示出上传的图片
                    $mood_pic = $imgName . ';' . $mood_pic;
                    $i = $i + 1;
                }
            }
        } else {
            $mood_pic = '';
        }

        $conn = new mysqli("", "root", "", "");
        if ($conn != null) {
            $conn->query("INSERT INTO personal_mood VALUES('$mood_id','$user_id','$mood_body',' $mood_date','$mood_pic')");
            $conn->close();
            $result = ['code' => '1', 'msg' => '发送成功'];
            echo json_encode($result);
        }
    }

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
}
