<?php
namespace app\admin\validate;
use think\Validate;

class Shuju extends Validate
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