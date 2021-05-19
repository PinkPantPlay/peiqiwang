<?php


namespace app\index\controller;


use think\Db;
use think\Model as OrderstaModel;
use think\Request;
use think\Session;

class User extends Base
{
    public function index(){
        $this->checklogin();
        $userid = Session::get('userid');
        $user = db('user')->where('id',$userid)->find();
        $address = db('address')->where('userid',$userid)->select();
        $sex = $user['sex'];
        $sex = $sex=='1'?'男':'女';
        $user['sex'] = $sex;
        $this->assign('address',$address);
        $this->assign('user',$user);
        return $this->fetch('user/index');
    }
    public function orderslist(){
        $this->checklogin();
        $userid = Session::get('userid');
        $order = db('orders')->where('id',$userid)->select();
    }
    public function address(){
        $this->checklogin();
        return $this->fetch('user/address');
    }
    public function addressadd(Request $request){
        $this->checklogin();
        $userid = Session::get('userid');
        $data = $request->param();
        $data['userid'] = $userid;
//        print_r($data);
        $result = $this->validate($data, 'User.address');
        if (true !== $result) {
            $this->error($result);
        } else {
            $res = db('address')->insert($data);
            if ($res) {
                // $this->finish('添加成功');
                // exit;
                // $this->success('添加成功', 'user/index');
                echo 1;
            } else {
                // $this->warning('添加失败，请重试');
                // exit;
                // $this->error('添加失败，请重试');
                echo 2;
            }
        }
    }
    public function verifyErr(Request $request){
        $username = $request->param('username');
//        echo $username;die;
        $res = Db::name('user')->where('username',$username)->find();
//        echo $res;die;
        if ($res==NULL){
            echo 1;
        }else{
            echo 0;
        }
    }
    public function ordersave(Request $request){
        if (request()->isPost()){
            $userid = Session::get('userid');
            $data=input('post.');
            if (empty($data['addressid'])){
                $this->error('请先添加收件人信息');
            }
            $address = db('address')->where('id',$data['addressid'])->find();
            $cart = db('cart')->join('shuju','shuju.id=cart.shujuid')->join('category','category.id=shuju.categoryid')->field('cart.*,category.id as categoryid')->where('userid',$userid)->select();
//            echo db('cart')->getLastSql();die;
            $data1 = array(
                'onumber'=>time().mt_rand(100,999),
                'userid'=>$userid,
                'nickname'=>$address['nickname'],
                'tel'=>$address['tel'],
                'address'=>$address['address'],
                'content'=>$data['content'],
                'addtime'=>date('Y-m-d H:i:s',time()),
                'status'=>0,
                'total'=>$data['total'],
                'zffs'=>'支付宝',
                'shfs'=>'快递',
                'zhuangtai'=>'已付款',
            );
            $res = db('orders')->insert($data1);
            $nid = db('orders')->getLastInsID();
//            echo '<pre>';
//            print_r($cart);echo '</pre>';die;
//            echo $nid;die;
//            db('ordersta')->insertAll($cart);
            $data2 = array();
            $i = 0;
            foreach ($cart as $v){
                $data2[$i]=array(
                    'ordersid'=>$nid,
                    'shujuid'=>$v['shujuid'],
                    'price'=>$v['price'],
                    'nums'=>$v['nums'],
                    'addtime'=>date('Y-m-d H:i:s',time()),
                    'userid'=>$userid,
                    'categoryid'=>$v['categoryid'],
                    'zhuangtai'=>'已付款',
                );
                db('shuju')->where("id",$v['shujuid'])->setDec('nums',$v['nums']);
                $i++;
            }
            $res1 = db('ordersta')->insertAll($data2);
//            echo $res1;die;
            if ($res){
                db('cart')->where('userid',$userid)->delete();
                $this->redirect('user/ordersdetail',['id'=>$nid]);
            }else{
                $this->error('提交失败');
            }
        }
    }
    public function addrdel(){
        $id = input('id');
        $res = db('address')->where('id',$id)->delete();
        if ($res){
            // $this->success('删除成功');
            echo 1;
        }else{
            // $this->error('删除失败，请联系管理员');
            echo 2;
        }
    }
    public function edit(){
        $userid = Session::get('userid');
        $user = db('user')->where('id',$userid)->find();
        $this->assign('user',$user);
        return $this->fetch('user/edit');
    }
    public function update(Request $request){
        $userid = Session::get('userid');
        $input = $request->param();
//        print_r($input);die;
        if (request()->isPost()){
            $data = array(
                'nickname'=>$input['nickname'],
                'sex'=>$input['sex'],
                'tel'=>$input['tel'],
            );
            $file = request()->file('img');
            if($file){
                $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
                if($info){
                    $data['img'] =  $info->getSaveName();
                }else{
                    echo $file->getError();
                }
            }
            $res = db('user')->where('id',$userid)->update($data);
            if ($res){
                $this->success('编辑成功');
            }else{
                $this->error('编辑失败');
            }
        }
    }
    public function updatepwd(Request $request){
        $userid = Session::get('userid');
        if (request()->isPost()){
            $input = $request->param();
            $user = db('user')->where("id",$userid)->find();
            if (md5($input['opassword']) === $user['password']){
                $result = $this->validate($input, 'User.updatepwd');
                if ($result){
                    $res = db('user')->where("id",$userid)->update(['password'=>md5($input['password'])]);
                    if ($res){
                        $this->success('密码修改成功','user/index');
                    }else{
                        $this->error('数据执行有误，请重新修改');
                    }
                }else{
                    $this->error('新密码格式有误');
                }
            }else{
                $this->error('原密码输入有误');
            }
        }
    }
    public function orders(){
        $userid = Session::get('userid');
        $orders = db('orders')->where('userid',$userid)->where("status",'0')->select();
        $this->assign('orders',$orders);
        return $this->fetch('user/orders');
    }
    public function ordersdetail(){
        $id = input('id');
        $orders = db('orders')->where('id',$id)->find();
        $ordersta = db('ordersta')->join('shuju','shuju.id=ordersta.shujuid')->field("ordersta.*,shuju.title")->where('ordersid',$id)->select();
        if (empty($orders['kuaidi'])){
            $orders['kuaidi']='暂无数据';
            $orders['knumber']='暂无数据';
        }
        $this->assign('orders',$orders);
        $this->assign('ordersta',$ordersta);
        return $this->fetch('user/ordersdetail');
    }
    public function ordersdel(){
        $id = input('id');
        $res = db('orders')->where('id',$id)->update(['status'=>'1']);
        if ($res){
            // $this->success('删除成功');
            echo 1;
        }else{
            // $this->error('删除失败，请重试');
            echo 2;
        }
    }
    public function cancel(){
        $id = input('id');
//        echo $id;die;
        $res = db('orders')->where('id',$id)->update(['zhuangtai'=>'申请取消']);
//        echo $res;die;
        if ($res){
            echo 1;
        }else{
            echo 2;
        }
    }
    public function myart(){
        $this->checklogin();
        $userid = Session::get('userid');
        $myart = db('bbs')->where("userid",$userid)->where("isno",'0')->select();
        $this->assign('myart',$myart);
        return $this->fetch('user/myart');
    }
}