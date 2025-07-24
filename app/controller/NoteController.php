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
        $user_id = $clientService->get_user_id();
        $categoryList = \app\model\Category::where('user_id', $user_id)->select();
        $notes = \app\model\Note::where('user_id', $user_id)->select();
        $list = [];
        foreach ( $categoryList as $category ) {
            $list[] = $category->hidden([ 'user_id', 'create_time', 'update_time' ]);
        }
        foreach ( $notes as $note ) {
            $list[] = $note->hidden([ 'user_id', 'content' ]);
        }
        return json($list);
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request, ClientService $clientService)
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
            return json([ 'error' => $validate->getError() ]);
        }

        $id = $request->post('id');
        $title = $request->post('title');
        $content = $request->post('content');
        if ( $id ) {
            $note = Note::find($id);
            if ( ! $note ) {
                return json([ 'error' => 'note not found' ]);
            }
            if ( $note->user_id !== $clientService->get_user_id() ) {
                return json([ 'error' => 'note not found.' ]);
            }
            $note->title = $title;
            $note->content = $content;
            $note->save();
            return json($note);
        } else {
            $note = new Note();
            $note->user_id = $clientService->get_user_id();
            $note->category_id = $request->post('category_id/d');
            $note->title = $title;
            $note->content = $content;
            $note->save();
            return json($note);
        }
    }

    public function get( Request $request, ClientService $clientService )
    {
        $validate = \think\facade\Validate::rule([
            'id' => 'require|min:1|max:7|number|integer',
        ]);
        if ( ! $validate->check($request->post()) ) {
            return json([ 'error' => $validate->getError() ]);
        }
        $id = $request->post('id/d');
        $user_id = $clientService->get_user_id();
        $note = Note::find($id);
        if ( ! $note ) {
            return json([ 'error' => 'note not found' ]);
        }
        if ( $note->user_id !== $user_id ) {
            return json([ 'error' => 'note not found.' ]);
        }
        return json($note);
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

    /**
     * 搜索笔记
     *
     * @param  \think\Request  $request
     * @param  ClientService  $clientService
     * @return \think\Response
     */
    public function search(Request $request, ClientService $clientService)
    {
        $validate = \think\facade\Validate::rule([
            'keyword' => 'max:100',
            'category_id' => 'integer',
            'limit' => 'integer|between:1,100',
            'page' => 'integer|min:1'
        ]);
        
        if (!$validate->check($request->param())) {
            return json(['error' => $validate->getError()]);
        }
        
        $user_id = $clientService->get_user_id();
        $keyword = $request->param('keyword', '');
        $category_id = $request->param('category_id/d', 0);
        $limit = $request->param('limit/d', 20);
        $page = $request->param('page/d', 1);
        
        $query = Note::with(['category'])
            ->where('user_id', $user_id);
        
        if (!empty($keyword)) {
            $query->where(function($q) use ($keyword) {
                $q->where('title', 'like', '%' . $keyword . '%')
                  ->whereOr('content', 'like', '%' . $keyword . '%');
            });
        }
        
        if ($category_id > 0) {
            $query->where('category_id', $category_id);
        }
        
        $total = $query->count();
        $notes = $query->order('update_time', 'desc')
            ->limit($limit)
            ->page($page)
            ->select();
        
        $list = [];
        foreach ($notes as $note) {
            $noteData = $note->toArray();
            unset($noteData['user_id']);
            if ($note->category) {
                $noteData['category'] = $note->category->hidden(['user_id', 'create_time', 'update_time'])->toArray();
            }
            $list[] = $noteData;
        }
        
        return json([
            'total' => $total,
            'page' => $page,
            'limit' => $limit,
            'data' => $list
        ]);
    }
}
