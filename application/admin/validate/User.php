<?php


namespace app\admin\validate;

use think\Validate;

class User extends Validate
{

    protected $rule = [
        'username' => 'require|unique:user|alphaNum',
        'password' => 'require|max:25',
        'nickname' => 'require|max:25',
        'sex' => 'require|in:1,2',
        'photo' => 'require',
        'tel' => 'require|regex:1[0-9]{10}'
    ];

    protected $message = [

        'username.require' => '用户名必须填写',
        'username.unique' => '用户名已存在，请重新填写',
        'username.alphaNum' => '用户名必须由字母数字组成',
        'password.require' => '密码必须填写',
        'password.max' => '密码不能超过25个字符',
        'nickname.require' => '名称必须填写',
        'nickname.max' => '姓名不能超过25个字符',
        'sex.require' => '性别必须选择',
        'sex.in' => '性别必须选择',
        'photo.require' => '图片必须上传',
        'tel.require' => '手机必须填写',
        'tel.regex' => '请填写正确的手机号'
    ];

    protected $scene = [
        'save' => ['username', 'password', 'nickname', 'sex',  'tel'],
        'update' => ['nickname', 'sex', 'tel'],
        'update_self' => ['nickname', 'sex', 'tel']
    ];
}