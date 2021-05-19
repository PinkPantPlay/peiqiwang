<?php


namespace app\index\controller;


use think\Db;

class Notice extends Base
{
    public function index(){
        $notice = Db::name('content1')->order("addtime","desc")->paginate(10);
        $this->assign('notice',$notice);
        return $this->fetch('notice/index');
    }
    public function detail(){
        $id = input('id');
        db('content1')->where("id",$id)->setInc("apv");
        $notice = Db::name('content1')->where("id",$id)->find();
        $this->assign('notice',$notice);
        return $this->fetch('notice/detail');
    }
}