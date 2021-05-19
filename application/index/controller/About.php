<?php


namespace app\index\controller;


use think\Db;

class About extends Base
{
    public function index(){
        $about = Db::name('about')->find();
        $this->assign('about',$about);
        return $this->fetch('about/index');
    }
}