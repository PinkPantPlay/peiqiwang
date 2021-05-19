<?php

namespace app\index\controller;

use think\Controller;
use think\Request;
use think\Db;

class Index extends Base
{
    public function index(){
        $banner = Db::name('ad')->limit(5)->select();
        $recomm = Db::name('shuju')->where("isnice","1")->order("addtime","desc")->limit(5)->select();
        $news = Db::name('shuju')->order("addtime","desc")->limit(10)->select();
        $hotarticle = Db::name('bbs')->where("click",">","99")->order("addtime","desc")->limit(5)->select();
        $a = 0;
        $this->assign('banner',$banner);
        $this->assign('recomm',$recomm);
        $this->assign('news',$news);
        $this->assign('hotarticle',$hotarticle);
        $this->assign('a',$a);
        return $this->fetch('index');
    }
}