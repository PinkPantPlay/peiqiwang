<?php


namespace app\index\validate;

use think\Validate;

class Bbs extends Validate
{

    protected $rule = [
        'content' => 'require'
    ];

    protected $message = [
        'content.require' => '请填写内容',
    ];

    protected $scene = [
        'save' => ['content']
    ];
}