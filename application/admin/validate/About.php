<?php


namespace app\admin\validate;

use think\Validate;

class About extends Validate
{

    protected $rule = [
        'editorValue' => 'require',
    ];

    protected $message = [
        'editorValue.require' => '必须填写',
    ];

    protected $scene = [
        'save' => ['editorValue'],
        'update' => ['editorValue']
    ];
}