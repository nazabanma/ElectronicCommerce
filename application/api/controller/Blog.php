<?php

namespace app\api\controller;

use think\Controller;
use think\Request;
use app\api\model\BlogInfo;

class Blog extends Controller
{asdsad

    private $model;

    function _initialize()
    {
        $this->model = new BlogInfo();
    }

    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        return json($this->model->field('id,name,src')->select());
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        $name = $request->param('name');
        $content = $request->param('content');
        $src = $request->param('src');
        if (!($name && $content && $src)) {
            return json(['statusCode' => 404, 'msg' => '缺少参数']);
        }
        $model = new BlogInfo([
            'id'        => time(),
            'name'      => $name,
            'content'   => $content,
            'src'       => $src
        ]);

        $res = $model->save();

        if (!$res) {
            return json(['statusCode' => 500, 'msg' => 'error']);
        }
        return json(['statusCode' => 200]);
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        $data = $this->model::get($id);
        if (is_null($data)) {
            return json(['statusCode' => '404', "statusMsg" => null]);
        }
        return (json(["statusCode" => 200, "data" => $data]));
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        $data = $this->model::get($id);
        $resutl = $data->delete();
        if (!$resutl) {
            return json(['statusCode' => 500, 'msg' => 'delete error']);
        }
        return json(['statusCode' => 200, 'id' => $id]);
    }
}
