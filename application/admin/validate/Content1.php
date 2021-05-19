<?php
namespace app\admin\validate;
use think\Validate;

class Content1 extends Validate
{

    protected $rule = [
        'title' => 'require',
    ];

    protected $message = [
        'title.require' => '名称必须填写',
    ];

    protected $scene = [
        'save' => ['title'],
        'update' => ['title']
    ];
}