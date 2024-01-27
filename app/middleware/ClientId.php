<?php
declare (strict_types = 1);

namespace app\middleware;


class ClientId
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
        $client_id = $request->header('client-id');
        if ( ! $client_id ) {
            $client_id = date('YmdHis') . '_' . rand(1000, 9999) . random_id(4);
        }
        $request->client_id = $client_id;
        $response = $next($request);
        $response->header([
            'client-id' => $client_id,
        ]);
        return $response;
    }
}
