<?php
declare (strict_types = 1);

namespace app\controller;

use think\Request;
use app\model\Category;

class CategoryController
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function list( Request $request )
    {
        if ( ! $request->has('parent_id', 'post') ) {
            return 'parent_id is required';
        }

        $parent_id = $request->post('parent_id');
        $list = Category::where('parent_id', $parent_id)
            ->where('user_id', 2)
            ->select();
        return json($list);
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
        //
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        //
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
        //
    }
}
