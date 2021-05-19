<?php


namespace app\index\controller;


use think\Db;
use think\Model as CartModel;
use think\Request;
use think\Session;

class Cart extends Base
{
    public function index(){
        $this->checklogin();
        $userid = Session::get('userid');
        $cart = Db::name('cart')->join('shuju','shuju.id=cart.shujuid')->where('userid',$userid)->field("cart.*,shuju.img,shuju.title,shuju.sprice")->select();
        $total = 0;
        foreach ($cart as $v){
            $total = $total + $v['price']*$v['nums'];
        }
        $this->assign('total',$total);
        $this->assign('cart',$cart);
        return $this->fetch('cart/index');
    }
    public function getNum(Request $request){
        $data = $request->param();
        $sid = $data['shujuid'];
        $cid = $data['cid'];
        $n = $data['n'];
        $price = $data['price']*$n;
        $total = $data['total'];
        $num = Db::name('shuju')->where('id',$sid)->value('nums');
//        echo $num;die;
        if ($n<=$num){
            Db::table('cart')->where('id', $cid)->update(['nums' => $n,'price' => $price]);
            $total = $total-$data['price']+$price;
//            echo Db::table('cart')->getLastSql();die;
        }
        return array('num'=>$num,'total'=>$total);
    }
    public function save(Request $request)
    {
        $this->checklogin();
        if(request()->isPost()){
            $data=input('post.');
            $userid = Session::get('userid');
            $shuju = db('shuju')->find($data['shujuid']);
            if($shuju['nums']<$data['nums']){
                $this->warning('商品库存不足','','3');
            }
            $chaxun = db('cart')->where('userid',$userid)->where('shujuid',$data["shujuid"])->find();
//            echo $chaxun;die;
            if(isset($chaxun)){$this->warning('已经加入购物车！','','4');}
            $ret = db('cart')->insert([
                'userid' => $userid,
                'shujuid' => $data['shujuid'],
                'price' => $data['price'],
                'nums' => $data['nums']
            ]);
            if (false === $ret) {
                $this->warning('提交失败！','','2');
                // echo 1;
            } else {
                $this->finish('已经为您加入购物车');
                // echo 2;
            }
        }
    }
    public function cutone(){
        $id = input('id');
        $data = db('cart')->where("id",$id)->find();
        if ($data['nums']>1){
            db('cart')->where("id",$id)->setDec('nums');
            echo 1;
        }else{
            echo 2;
        }
    }
    public function addone(){
        $id = input('id');
        $cart = db('cart')->where("id",$id)->find();
        $pro = db('shuju')->where("id",$cart['shujuid'])->find();
        if ($cart['nums']<$pro['nums']){
            db('cart')->where("id",$id)->setInc('nums');
            echo 1;
        }else{
            echo 2;
        }
    }
    public function del(){
        $id = input('id');
        $res = db('cart')->where('id',$id)->delete();
        if ($res){
            echo 1;
        }else{
            echo 2;
        }
    }
    public function submit(){
        $userid = Session::get('userid');
        $cart = db('cart')->join('shuju','cart.shujuid=shuju.id')->field('cart.*,shuju.title')->where('userid',$userid)->select();
        if (empty($cart)){
            $this->error('请先添加商品到购物车！');
        }
        $address = db('address')->where('userid',$userid)->select();
        $total = 0;
        foreach ($cart as $k=>$v){
            $price = $v['price']*$v['nums'];
            $cart[$k]['price']=$price;
            $total = $total + $price;
        }
        $this->assign('cart',$cart);
        $this->assign('address',$address);
        $this->assign('total',$total);
        return $this->fetch('cart/submit');
    }
}