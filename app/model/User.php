<?php
declare (strict_types = 1);

namespace app\model;

use think\Model;

/**
 * @mixin \think\Model
 */
class User extends Model
{
    // 设置字段信息
    protected $schema = [
        'id' => 'int',
        'name' => 'string',
        'email' => 'string',
        'password' => 'string',
        'status' => 'int', // 0 未激活 1 已激活 2 禁用 3 锁定 4 删除
        'last_login_time' => 'datetime',
        'failed_login_count' => 'int',
        'last_failed_login_time' => 'datetime',
        'last_login_ip' => 'string',
        'info' => 'string',
        'create_time' => 'datetime',
        'update_time' => 'datetime',
    ];

    // 设置json类型字段
    protected $json = ['info'];
}
