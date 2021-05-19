<?php


namespace app\admin\validate;

use think\Validate;

class Login extends Validate
{

    protected $rule = [
        'username' => 'require',
        'password' => 'require',
        'confirm_password' => 'require|confirm:password',
        'old_password' => 'require'
    ];

    protected $message = [
        'username.require' => '用户名必填',
        'password.require' => '密码必填',
        'confirm_password.require' => '请填写确认密码',
        'confirm_password.confirm' => '两次密码不一致',
        'old_password.require' => '当前密码必填'
    ];

    protected $scene = [
        'login' => ['username', 'password'],
        'modify' => ['password', 'confirm_password', 'old_password']
    ];
}