<?php
declare (strict_types = 1);

namespace app\controller;

use think\Request;
use app\controller\BaseController;
use app\model\Category;
use app\service\ClientService;
use think\facade\Validate;

class CategoryController extends BaseController
{

    public function list( Request $request, ClientService $clientService )
    {
        $list = Category::where('user_id', $clientService->get_user_id())->select();
        return json([ 'list' => $list ]);
    }

    public function create( Request $request, ClientService $clientService )
    {
        $validate = \think\facade\Validate::rule([
                'parent'  => 'require|min:1|max:7|number|integer',
                'name'   => 'require|min:1|max:16',
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
        $parent_id = $request->post('parent/d'); // 如果加了 /d, "a1" 会被转换成 0
        $user_id = $clientService->get_user_id();
        if ( $parent_id !== 0 ) {
            $parent = Category::find($parent_id);
            if ( ! $parent ) {
                return json([ 'error' => 'parent not found' ]);
            }
            if ( $parent->user_id !== $user_id ) {
                return json([ 'error' => 'parent not found.' ]);
            }
        }
        $category = new Category();
        $category->user_id = $user_id;
        $category->parent_id = $parent_id;
        $category->name = $request->post('name/s');
        $category->save();
        return json(['id' => $category->id]);
    }

    public function rename ( Request $request, ClientService $clientService ) {
        $validate = \think\facade\Validate::rule([
            'id'  => 'require|min:1|max:7|number|integer',
            'name'   => 'require|min:1|max:16',
        ])->message([]);
        if ( ! $validate->check($request->post()) ) {
            return json([ 'error' => $validate->getError() ]);
        }
        $id = $request->post('id/d');
        $name = $request->post('name/s');
        $user_id = $clientService->get_user_id();
        $category = Category::find($id);
        if ( ! $category ) {
            return json([ 'error' => 'category not found' ]);
        }
        if ( $category->user_id !== $user_id ) {
            return json([ 'error' => 'category not found.' ]);
        }
        $category->name = $name;
        $category->save();
        return json(['id' => $category->id]);
    }

    public function move ( Request $request, ClientService $clientService ) {
        $validate = \think\facade\Validate::rule([
            'id'  => 'require|min:1|max:7|number|integer',
            'parent'  => 'require|min:1|max:7|number|integer',
        ])->message([]);
        if ( ! $validate->check($request->post()) ) {
            return json([ 'error' => $validate->getError() ]);
        }
        $id = $request->post('id/d');
        $parent_id = $request->post('parent/d');
        $user_id = $clientService->get_user_id();
        $parent = Category::find($parent_id);
        if ( ! $parent ) {
            return json([ 'error' => 'parent not found' ]);
        }
        if ( $parent->user_id !== $user_id ) {
            return json([ 'error' => 'parent not found.' ]);
        }
        $category = Category::find($id);
        if ( ! $category ) {
            return json([ 'error' => 'category not found' ]);
        }
        if ( $category->user_id !== $user_id ) {
            return json([ 'error' => 'category not found.' ]);
        }
        $category->parent_id = $parent_id;
        $category->save();
        return json(['id' => $category->id]);
    }
}
