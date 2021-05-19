<?php


namespace app\index\validate;

use think\Validate;

class User extends Validate
{

    protected $rule = [
        'username' => 'require|unique:user',
        'password' => 'require|max:25',
        'nickname' => 'require|max:25',
        'sex' => 'require|in:1,2',
        'tel' => 'require|regex:1[0-9]{10}',
        'address'=>'require|max:150'
    ];

    protected $message = [
        'username.require' => '用户名必须填写',
        'username.unique' => '用户名已存在，请重新填写',
        'password.require' => '密码必须填写',
        'password.max' => '密码不能超过25个字符',
        'nickname.require' => '姓名必须填写',
        'nickname.max' => '姓名不能超过25个字符',
        'sex.require' => '性别必须选择',
        'sex.in' => '性别必须选择',
        'tel.require' => '手机必须填写',
        'tel.regex' => '请填写正确的手机号',
        'address.require' => '地址必须填写',
        'address.max' => '姓名不能超过150个字符',
    ];

    protected $scene = [
        'save' => ['username', 'password', 'nickname', 'tel'],
        'update' => ['nickname','tel'],
        'address'=>['nickname','tel','address'],
        'updatepwd'=>['password'],
    ];
}