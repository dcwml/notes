<?php
declare (strict_types = 1);

namespace app\middleware;

use app\service\ClientService;

class ApiAuth
{
    /**
     * 处理请求
     *
     * @param \think\Request $request
     * @param \Closure       $next
     * @return Response
     */
    public function handle($request, \Closure $next)
    {
        $clientService = app()->make(ClientService::class);
        $user_id = $clientService->get_client_data( 'user_id' );
        if ( $user_id ) {
            $user = \app\model\User::find($user_id);
            if ( ! $user ) {
                $clientService->clear_client_data();
                return json( [ 'code' => 401, 'msg' => 'Unauthorized', 'error' => '请登录.' ] );
            }
            if ( $user->status !== 1 ) {
                $clientService->clear_client_data();
                return json( [ 'code' => 401, 'msg' => 'Unauthorized', 'error' => '请登录!' ] );
            }
            return $next($request);
        } else {
            return json( [ 'code' => 401, 'msg' => 'Unauthorized', 'error' => '请登录' ] );
        }
        // return response('Unauthorized', 401);
    }
}
