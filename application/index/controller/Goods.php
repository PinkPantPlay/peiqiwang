<?php


namespace app\index\controller;
use think\Controller;
use think\Request;
use think\Db;

class Goods extends Base
{
    public function index(){
        $category = Db::name('category')->where("status","0")->select();
        $categoryid = input('categoryid');
        $keyword = urldecode(input('keyword',''));
        // $keyword1 = '';
        if (empty($keyword)){
            $keyword1 = $keyword;
        }
        if (!empty($categoryid)){
            $goods = Db::name('shuju')->where(["status"=>"0","categoryid"=>$categoryid,])->where("title","like","%$keyword%")->order("id","desc")->paginate(20);
        } else{
            $goods = Db::name('shuju')->where(["status"=>"0",])->where("title","like","%$keyword%")->order("id","desc")->paginate(20);
        }
        $this->assign('keyword',$keyword);
        $this->assign('goods',$goods);
        $this->assign('category',$category);
        return $this->fetch('goods/index');
    }
    public function recommend(){
        $goods = Db::name('shuju')->where(['status'=>0,"isnice"=>1])->order("id","desc")->paginate(20);
        $this->assign('goods',$goods);
        return $this->fetch('goods/recommend');
    }
    public function goodsdetail(){
        $sid = input('id');
        $pro = Db::name('shuju')->where('id',$sid)->find();
        $this->assign('pro',$pro);
        return $this->fetch('goods/goods-detail');
    }
    public function getnums(){
        $id = input('pid');
        $num = Db::name('shuju')->where('id',$id)->value('nums');
        echo $num;
    }
}