<?php

namespace app\admin\controller;

use app\admin\model\Admin;
use app\admin\model;
use think\Controller;
use think\Request;
use app\admin\model\User as UserModel;
use app\admin\model\Admin as AdminModel;
use app\admin\model\Instructor as InstructorModel;
use think\Session;



class Login extends \think\Controller
{
    
    public function index()
    {
		$website = new model\Website();
        $websiteres = $website->getWebsite();
		$this->assign('websiteres',$websiteres);
		$auth = Session::get('auth');
        if ($auth) {
            $this->redirect(url('index/index'));
        }
        return $this->fetch();
    }
    
    public function login(Request $request)
    {
        // Session::clear();
        if ($request->isAjax()) {
            Session::clear();
            $data = $request->param();
            $result = $this->validate($data, 'Login.login');
            if (true === $result) {
                $verifycode = trim(input('post.verification'));
                //验证验证码
                if(!captcha_check($verifycode)){
                    exit(json_encode(array('code'=>1,'msg'=>'验证码错误')));
                }
                $loginModel = new Admin();
                if ($data['role'] == '3') {
                    $loginModel = new UserModel();
                } elseif ($data['role'] == '2') {
                    $loginModel = new InstructorModel();
                }
                $isLoginSuccess = $loginModel->login($data);
                if ($isLoginSuccess) {
                    Session::set('username', $data['username']);
                    return ['code' => 0, 'msg' => '登录成功'];
                } else {
                    return ['code' => 1, 'msg' => '请检查帐号密码是否正确'];
                }
            } else {
                return ['code' => 1, 'msg' => $result];
            }
        } else {
            $this->error('请求类型错误，请通过合法途径请求！');
        }
    }

    
    public function login_out()
    {
        Session::clear();
        $this->redirect(url('login/index'));
    }
	public function loginuser_out()
    {
        Session::clear();
        $this->redirect(url('../index/login/index'));
    }

    
    public function modify_password()
    {
        return $this->fetch();
    }

    
    public function save_password(Request $request)
    {
        if ($request->isAjax()) {
            $data = $request->param();
            $result = $this->validate($data, 'Login.modify');
            if (true === $result) {
                $loginModel = new AdminModel();
                $modelKey = 'username';
                $auth = Session::get('auth');
                $username = Session::get('username');
                if ($auth === 3) {
                    $loginModel = new UserModel();
                    $modelKey = 'username';
                } elseif ($auth === 2) {
                    $loginModel = new InstructorModel();
                }
                $authObj = $loginModel->where(["$modelKey" => $username, 'password' => md5($data['old_password'])])->find();
                if ($authObj) {
                    $authObj->password = md5($data['password']);
                    $ret = $authObj->allowField(true)->save();
                    if (!!!$ret) {
                        return ['code' => 1, 'msg' => '修改失败！执行如下操作：' . $loginModel->getLastSql()];
                    } else {
                        return ['code' => 0, 'msg' => '修改成功！'];
                    }
                } else {
                    return ['code' => 1, 'msg' => '修改失败！请检查密码是否正确' . $loginModel->getLastSql()];
                }
            } else {
                return ['code' => 1, 'msg' => $result];
            }
        } else {
            $this->error('请求类型错误，请通过合法途径请求！');
        }
    }
}
