<?php
namespace app\controller;

use think\Request;
use app\BaseController;
use app\model\User;
use app\model\Client;

class IndexController extends BaseController
{
    public function index( Request $request )
    {

        // $user = new User();
        // $user->name = 'b';
        // $user->email = 'b@c.com';
        // $user->password = password_hash( 'a', PASSWORD_BCRYPT );
        // $user->info = [
        //     'a' => 1,
        // ];
        // $user->save();
        // echo $user->id;
        // // $user = User::find(2);
        // // echo $user->info->a;
        return view('index', [
            'count' => User::where('status', 1)->count(),
         ]);
    }

    public function register ( Request $request ) {
        $validate = \think\facade\Validate::rule([
                'name'  => 'require|max:32',
                'email' => 'require|email|max:64',
                'password'   => 'require|min:6|max:32',
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

        $user = new User();
        $user->name = $request->post('name');
        $user->email = $request->post('email');
        $user->status = 1;
        $user->password = password_hash( $request->post('password'), PASSWORD_BCRYPT );
        $user->save();
        // return $user->id;
        return redirect('/');
    }

    public function login ( Request $request, \app\service\ClientService $clientService ) {
        $validate = \think\facade\Validate::rule([
                'name'  => 'require|max:32',
                'password'   => 'require|min:6|max:32',
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

        $user = User::where('name', $request->post('name'))->find();
        if ( ! $user ) {
            return json([ 'error' => '用户名或者密码不正确' ]);
        }
        if ( $user->status === 0 ) {
            return json([ 'error' => '用户尚未激活' ]);
        }
        if ( $user->status === 2 ) {
            return json([ 'error' => '用户已禁止登录' ]);
        }
        if ( $user->status === 3 ) {
            if ( $user->failed_login_count >= 3 ) {
                if ( time() - strtotime($user->last_failed_login_time) < ( $user->failed_login_count * 60 ) ) {
                    return json([ 'error' => '用户已被锁定至 ' . date('Y-m-d H:i:s', strtotime($user->last_failed_login_time) + ( $user->failed_login_count * 60 )) . ' 请稍后再试' ]);
                }
            }
        }
        if ( $user->status === 4 ) {
            return json([ 'error' => '用户名或者密码不正确！' ]);
        }
        if ( ! password_verify( $request->post('password'), $user->password ) ) {
            $user->failed_login_count++;
            $user->last_failed_login_time = date('Y-m-d H:i:s');
            $user->save();
            return json([ 'error' => '用户名或者密码不正确。' ]);
        }
        $user->failed_login_count = 0;
        $user->last_login_time = date('Y-m-d H:i:s');
        $user->last_login_ip = $request->ip();
        $user->save();
        $clientService->set_client_data('user_id', $user->id);
        // return redirect('/notes');
        // return json($user->hidden(['password'])->toArray());
        return json($user->visible(['id', 'name', 'email'])->toArray());
    }

    public function get_client_info( \app\service\ClientService $service ) {
        $service->set_client_data('qwert', '123');
    }

    public function changePassword( Request $request, \app\service\ClientService $clientService ) {
        $validate = \think\facade\Validate::rule([
                'old_password' => 'require|min:6|max:32',
                'new_password' => 'require|min:6|max:32',
                'confirm_password' => 'require|min:6|max:32',
            ])
            ->message([
                'old_password.require' => '原密码必须填写',
                'new_password.require' => '新密码必须填写',
                'confirm_password.require' => '确认密码必须填写',
                'new_password.min' => '新密码至少6位',
                'confirm_password.min' => '确认密码至少6位',
            ]);

        if ( ! $validate->check($request->post()) ) {
            return json([ 'error' => $validate->getError() ]);
        }

        if ( $request->post('new_password') !== $request->post('confirm_password') ) {
            return json([ 'error' => '新密码与确认密码不一致' ]);
        }

        $user_id = $clientService->get_client_data('user_id');
        if ( ! $user_id ) {
            return json([ 'error' => '用户未登录' ]);
        }

        $user = User::find($user_id);
        if ( ! $user ) {
            return json([ 'error' => '用户不存在' ]);
        }

        if ( ! password_verify( $request->post('old_password'), $user->password ) ) {
            return json([ 'error' => '原密码不正确' ]);
        }

        $user->password = password_hash( $request->post('new_password'), PASSWORD_BCRYPT );
        $user->save();

        return json([ 'success' => '密码修改成功' ]);
    }
}
