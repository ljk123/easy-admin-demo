<?php


namespace app\validate;

use easy\Validate;

class User extends Validate
{

    protected $rule = [
        'username' => 'required|between_len:3,20',
        'group_id' => 'required',
    ];
    protected $alias = [
        'username' => '用户名',
        'group_id' => '请选择分组',
    ];
}