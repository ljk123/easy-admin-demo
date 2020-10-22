<?php


namespace app\validate;

use easy\Validate;

class Group extends Validate
{

    protected $rule = [
        'name' => 'required|between_len:3,20'
    ];
    protected $alias = [
        'name' => '分组命名'
    ];
}