<?php
declare (strict_types = 1);

namespace app\model;

use think\Model;

/**
 * @mixin \think\Model
 */
class Note extends Model
{
    protected $schema = [
        'id' => 'integer',
        'user_id' => 'integer',
        'title' => 'string',
        'content' => 'text',
        'status' => 'integer', // 0 normal, 1 deleted
        'create_time' => 'datetime',
        'update_time' => 'datetime',
    ];
}
