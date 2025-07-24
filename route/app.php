<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\facade\Route;

Route::get('/', 'index/index');
Route::get('/get_client_info', 'index/get_client_info');
Route::post('/register', 'index/register');
Route::post('/login', 'index/login');

Route::group(function () {
	Route::get('/notes', 'note/index');
	Route::post('/note/get_list', 'note/get_list');
	Route::post('/note/save', 'note/save');
	Route::post('/note/get', 'note/get');
	Route::get('/note/search', 'note/search');

	Route::post('/category/list', 'category/list');
	Route::post('/category/create', 'category/create');
	Route::post('/category/rename', 'category/rename');

	Route::post('/change_password', 'index/changePassword');
})->middleware(\app\middleware\ApiAuth::class);
