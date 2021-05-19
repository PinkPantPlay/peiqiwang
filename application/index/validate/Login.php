<?php


namespace app\index\validate;

use think\Validate;

class Login extends Validate
{

    protected $rule = [
        'username' => 'require',
        'password' => 'require',
        'full_name' => 'require',
		'user_status' => 'require',
		'code' => 'require',
        'repassword' => 'require|confirm:password',
        'old_password' => 'require',
    ];

    protected $message = [
        'username.require' => '用户名必填',
        'username.unique' => '用户名已存在',
        'full_name.require' => '姓名必填',
        'password.require' => '密码必填',
		'code.require' => '验证码必填',
        'repassword.require' => '请填写确认密码',
        'repassword.confirm' => '两次密码不一致',
        'old_password.require' => '当前密码必填',
    ];

    protected $scene = [
        'login' => ['username', 'password'],
        'register' => ['username'=>'unique:user', 'password', 'password'],
        'modify' => ['password', 'repassword', 'old_password']
    ];
}