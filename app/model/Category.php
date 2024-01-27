<?php
declare (strict_types = 1);

namespace app\model;

use think\Model;

/**
 * @mixin \think\Model
 */
class Category extends Model
{
    protected $schema = [
        'id' => 'integer',
        'user_id' => 'integer',
        'parent_id' => 'integer',
        'name' => 'string',
        'create_time' => 'datetime',
        'update_time' => 'datetime',
    ];
}
