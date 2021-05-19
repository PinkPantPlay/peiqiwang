<?php


namespace app\admin\validate;


use think\Validate;

class Website extends Validate
{
    protected $rule = [
        'title' => 'require',
        'address' => 'require',
        'keyword' => 'require',
    ];

    protected $message = [
        'title.require' => '必须填写',
        'address.require' => '必须填写',
        'keyword.require' => '必须填写',
    ];

    protected $scene = [
        'save' => ['title','address','keyword'],
        'update' => ['editorValue']
    ];
}