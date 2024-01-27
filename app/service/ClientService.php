<?php
declare (strict_types = 1);

namespace app\service;

class ClientService extends \think\Service
{
    /**
     * 注册服务
     *
     * @return mixed
     */
    public function register()
    {
    	//
    }

    /**
     * 执行服务
     *
     * @return mixed
     */
    public function boot()
    {
        //
    }

    public function set_client_data ( string $key, $data ) {
        $file = $this->get_client_data_file();
        $client_data = $this->get_client_data_all( $file );
        if ( ! isset( $client_data['client_id'] ) ) {
            $client_data['client_id'] = $this->app->request->client_id;
            $client_data['create_time'] = date('Y-m-d H:i:s');
        }
        $client_data['update_time'] = date('Y-m-d H:i:s');
        $client_data[$key] = $data;
        file_put_contents($file, json_encode($client_data, JSON_PRETTY_PRINT));
    }

    public function get_client_data ( string $key ) {
        $client_data = $this->get_client_data_all();
        return isset( $client_data[$key] ) ? $client_data[$key] : null;
    }

    public function get_client_data_all ( string $file = '' ) {
        if ( ! $file ) $file = $this->get_client_data_file();
        $client_data = [];
        if ( file_exists($file) ) {
            $client_data = json_decode(file_get_contents($file), true);
        }
        return $client_data;
    }

    public function get_client_data_file () {
        $request = $this->app->request;
        $client_id = $request->client_id;
        $dir = runtime_path('client_data');
        if ( ! is_dir($dir) ) {
            mkdir($dir, 0755, true);
        }
        $file = $dir . $client_id . '.json';
        return $file;
    }

    /**
     * 删除当前 client_id 对应的数据
     */
    public function clear_client_data () {
        $request = $this->app->request;
        $client_id = $request->client_id;
        $dir = runtime_path('client_data');
        if ( ! is_dir($dir) ) {
            mkdir($dir, 0755, true);
        }
        $file = $dir . $client_id . '.json';
        if ( file_exists($file) ) {
            unlink($file);
        }
    }

    // ================================ ================================

    public function get_user_id () {
        return $this->get_client_data('user_id');
    }

    public function set_user_id ( int $user_id ) {
        $this->set_client_data('user_id', $user_id);
    }
}
