<?php

namespace app\index\model;

use think\Model;
use think\Session;

class User extends Model
{
    public function login($data)
    {
        $user = new User();
        $userObj = $user->where([
            'username' => $data['username'],
			//'type' => $data['type'],
            'password' => md5($data['password']),
        ])->find();
		//echo $userObj;die;
        if ($userObj) {
            Session::set('userid', $userObj['id']);
            Session::set('username', $userObj['username']);
			Session::set('type', $userObj['type']);
        } else {
            return false;
        }
        return $userObj;
    }
}
