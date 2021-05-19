<?php

namespace app\admin\model;

use think\Model;
use think\Session;

/*
 * 
 */

class User extends Model
{
    /**
     * 登录
     * @param $data
     * @return bool
     */
    public function login($data)
    {
        $user = new User();
        $userObj = $user->where([
            'username' => $data['username'],
			'status' => 0,
            'password' => md5($data['password'])
        ])->find();
		
		//die;
        if ($userObj) {
            Session::set('auth', 3);
            Session::set('useridm', $userObj['id']);
            Session::set('nicknamem', $userObj['nickname']);
			
        } else {
            return false;
        }
        return $userObj;
    }
}