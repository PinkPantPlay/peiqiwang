<?php

namespace app\admin\model;

use think\Model;
use think\Session;

/*
 * 登录模型
 */

class Admin extends Model
{
    /**
     * 管理员登录
     * @param $data
     * @return bool
     */
    public function login($data)
    {
        $admin = new Admin();
        $adminObj = $admin->where([
            'username' => $data['username'],
            'password' => md5($data['password'])
        ])->find();
        if ($adminObj) {
            Session::set('auth', 1);
            Session::set('auth_code', $adminObj['id']);
            Session::set('uname', $adminObj['uname']);
        } else {
            return false;
        }
        return $adminObj;
    }
}
