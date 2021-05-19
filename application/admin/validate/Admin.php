<?php


namespace app\admin\validate;

use think\Validate;

class Admin extends Validate
{

    protected $rule = [
        'username' => 'require|unique:admin',
		'password' => 'require|max:25',
        'uname' => 'require|max:25',
        'tel' => 'require|regex:1[0-9]{10}'
    ];

    protected $message = [
       	'username.require' => '用户名必须填写',
        'username.unique' => '用户名已存在，请重新填写',
        'password.require' => '密码必须填写',
        'password.max' => '密码不能超过25个字符',
        'uname.require' => '姓名必须填写',
        'uname.max' => '姓名不能超过25个字符',
        
        'tel.require' => '手机必须填写',
        'tel.regex' => '请填写正确的手机号'
    ];

    protected $scene = [
        'save' => ['uname', 'password', 'username', 'tel'],
        'update' => ['uname', 'tel'],
        'update_self' => ['uname', 'tel']
    ];
}