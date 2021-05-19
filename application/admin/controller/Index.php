<?php

namespace app\admin\controller;

use app\admin\model;
use think\Db;
use think\Response;
use think\Request;
use think\Session;

class Index extends \think\Controller
{
	public function index(Response $response, Request $request)
    {
        $menu = new model\Menu();
		$website = new model\Website();
        $auth = Session::get('auth');
        if (isset($auth)) {
            $menures = $menu->getNavMenus();
            $this->assign('menures', $menures);
			$websiteres = $website->getWebsite();
			$this->assign('websiteres',$websiteres);
            $fullname = Session::get('fullname');
            $this->assign('fullname', $fullname);
            $this->assign('auth', $auth);
        } else {
            $this->redirect(url('login/index'));
        }
		
        return $this->fetch();
    }

    public function home()
    {
        $auth = Session::get('auth');
        $new = Db::name('orders')->where("status","<>","-1")->order('addtime','desc')->limit(10)->select();
        $cancel = Db::name('orders')->where('zhuangtai','申请取消')->paginate(5);
        $this->assign('new', $new);
        $this->assign('cancel', $cancel);
        $this->assign('auth', $auth);
        $website = new model\Website();
		$websiteres = $website->getWebsite();
		$this->assign('websiteres',$websiteres);
		return $this->fetch();
    }
	public function left()
    {
        $menu = new model\Menu();
        $auth = Session::get('auth');
		$this->assign('auth', $auth);
        if (isset($auth)) {
            $menures = $menu->getNavMenus();
            $this->assign('menures', $menures);
        } else {
            $this->redirect(url('login/index'));
        }
		return $this->fetch();
    }
	public function top()
    {
        $website = new model\Website();
		$menu = new model\Menu();
        $auth = Session::get('auth');
        if (isset($auth)) {
           	$websiteres = $website->getWebsite();
			$this->assign('websiteres',$websiteres);
            $fullName = Session::get('full_name');
            $this->assign('full_name', $fullName);
            $this->assign('auth', $auth);
        } else {
            $this->redirect(url('login/index'));
        }
		return $this->fetch();
    }

    public function upload(Request $request)
    {
        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file('file');

        // 移动到框架应用根目录/public/uploads/ 目录下
        if ($file) {
            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');

            if ($info) {
                return ['code' => 0, 'msg' => '上传成功！', 'data' => $info->getSaveName()];
            } else {
                // 上传失败获取错误信息
                return ['code' => 1, 'msg' => '上传失败！'];
            }
        }
    }
}
