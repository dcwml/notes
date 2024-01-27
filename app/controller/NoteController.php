<?php
declare (strict_types = 1);

namespace app\controller;

use think\Request;
use app\model\Note;
use app\service\ClientService;

class NoteController
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $list = Note::where('status', 0)
            ->select();
        return view('index', [ 'list' => $list ]);
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function get_list( Request $request, ClientService $clientService )
    {
        $parent_id = $request->post('parent_id');
        if ( ! $parent_id ) {
            $parent_id = 0;
        }
        if ( ! is_numeric( $parent_id ) ) {
            $parent_id = 0;
        }
        $parent_id = intval( $parent_id );
        $user_id = $clientService->get_user_id();
        $categoryList = \app\model\Category::where('user_id', $user_id)->where('parent_id', $parent_id)->select();
        $list = Note::where('user_id', $user_id)
            ->where('category_id', $parent_id)
            ->where('status', 0)
            ->select();
        return json([
            'categories' => $categoryList,
            'notes' => $list
        ]);
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        $validate = \think\facade\Validate::rule([
                'title' => 'require|max:120',
                'id' => 'require|integer',
            ])
            ->message([
                // 'name.require' => '用户名必须填写',
                // 'name.max'     => '用户名不能超过25个字符',
                // 'email'        => '邮箱格式不正确'
            ])
            ;
        if ( ! $validate->check($request->post()) ) {
            return $validate->getError();
        }

        $id = $request->post('id');
        $title = $request->post('title');
        $content = $request->post('content');
        $model = new Note();
        if ( $id ) {
            $model->where('id', $id)->update([
                'title' => $title,
                'content' => $content,
                // 'update_time' => date('Y-m-d H:i:s'),
            ]);
        } else {
            $model->insert([
                'user_id' => 2,
                'title' => $title,
                'content' => $content,
                // 'create_time' => date('Y-m-d H:i:s'),
                // 'update_time' => date('Y-m-d H:i:s'),
            ]);
        }
        return redirect('/notes');
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
