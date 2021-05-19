<?php


namespace app\index\controller;
use think\Db;
use think\Model as UserModel;
use think\Validate;
use think\Request;
use think\Session;

class Login extends Base
{
    public function login(){
        $userid = Session::get('userid');
        if ($userid) {
            $this->redirect(url('index/index'));
        }
        return $this->fetch('login/login');
    }
    public function checkl(Request $request)
    {
        if(request()->isPost()){
            $data=input('post.');
//            print_r($data);die;
            $captcha=input("verify");
            //验证
            if (!captcha_check($captcha)){
//                $this->error('验证码输入有误');
                echo 2;exit;
            }
            if (empty($data['username'])){
//                exit(json_encode(array('status'=>1,'msg'=>'请输入用户名')));
                echo 3;exit;
            }
            if (empty($data['password'])){
//                exit(json_encode(array('status'=>2,'msg'=>'请输入密码')));
                echo 4;exit;
            }
            $isLoginSuccess = Db::name('user')->where(['username'=>$data['username'],'password'=>md5($data['password']),])->find();
//                var_dump($isLoginSuccess);die;
            if ($isLoginSuccess['status']==1){
                echo 6;exit;
            }
            if ($isLoginSuccess!==NULL) {
               Session::set('username', $data['username']);
               Session::set('userid', $isLoginSuccess['id']);
//               $this->success('登录成功！','index/user/index');
                echo 1;exit;
            }else{
//               $this->error('用户名或者密码错误！');
                echo 5;exit;
            }
        }
    }
    public function register()
    {
        $userid = Session::get('userid');
        if ($userid) {
            $this->redirect(url('index/index'));
        }
        return $this->fetch('login/register');
    }
    public function registersave(Request $request)
    {
//        echo 1;die;
        if(request()->isPost()){
            $data=input('post.');
//            print_r($data);die;
            $verifycode = $data['verification'];
            //验证验证码
            if(!captcha_check($verifycode)){
                $this->warning('验证码输入有误');
            }
            $result = $this->validate($data, 'User.save');

            if (true !== $result) {
                $this->error($result);
            } else {
                $ret = Db::name('user')->insert([
                    'username' => $data['username'],
                    'password' => md5($data['password']),
                    'nickname' => $data['nickname'],
                    'sex' => $data['sex'],
                    'tel' => $data['tel'],
                    'address' => $data['address'],
                    'type' => '用户',
                ]);
                if (false === $ret) {
                    $this->warning('注册失败');
                } else {
                    $this->finish('注册成功');
                }
            }
        } else {
            $this->warning('请求类型错误，请通过合法途径请求！');
        }
    }
    public function login_out()
    {
        Session::clear();
        $this->redirect(url('index/index'));
    }
}