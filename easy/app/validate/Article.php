<?php


namespace app\validate;

use easy\Validate;

class Article extends Validate
{

    protected $rule = [
        'title' => 'required|between_len:9,200',
    ];
    protected $alias = [
        'title' => '标题',
    ];
}