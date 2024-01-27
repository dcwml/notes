<?php
declare (strict_types = 1);

namespace app\middleware;

class CORS
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
        $response = $next($request);
        $response->header([
            'Access-Control-Allow-Origin' => $request->header('origin'),
            // 'Access-Control-Allow-Credentials' => 'true',
            'Access-Control-Allow-Headers' => $request->header('Access-Control-Request-Headers'), // 'Authorization, Content-Type, client_id',
            'Access-Control-Allow-Methods' => $request->header('Access-Control-Request-Method'), // 'GET, POST, PUT, DELETE, OPTIONS'
            'Access-Control-Expose-Headers' => 'client-id',
        ]);
        return $response;
    }
}
