<?php


namespace app\index\controller;
use think\Session;
use think\Db;

class Bbs extends Base
{
    public function index(){
        $categoryid = input('categoryid');
        if(!empty($categoryid)){
            $article = Db::name('bbs')->where("status","0")->where("isno",'0')->where("categoryid",$categoryid)->order("addtime","desc")->paginate(10);
        }else{
        $article = Db::name('bbs')->where("status","0")->where("isno",'0')->order("addtime","desc")->paginate(10);
        }
        $bbstype = Db::name('bbstype')->where("status",'0')->select();
        $this->assign('bbstype',$bbstype);
        $this->assign('article',$article);
        return $this->fetch('bbs/index');
    }
    public function articleadd(){
        $this->checklogin();
        if(request()->isPost()){
            $data=input('post.');
//            print_r($data);die;
            $userid = Session::get('userid');
            $result = $this->validate($data, 'Bbs.save');
            if (true !== $result) {
                $this->warning($result);
            } else {
                $data1 = array(
                    'userid' => $userid,
                    'title' => $data['title'],
                    'content' => $data['content'],
                    'categoryid' => $data['bbstype'],
                    'addtime'=> date("Y-m-d H:i:s",time()),
                );
                $ret = Db::name('bbs')->insert($data1);
                if (false === $ret) {
                    $this->warning('提交失败！');
                } else {
                    $this->finish('提交成功！');
                }
            }
        } else {
            $this->warning('请求类型错误，请通过合法途径请求！');
        }
    }
    public function detail(){
        $id = input('id');
        db('bbs')->where("id",$id)->setInc('click');
        $nowuid = Session::get('userid')?Session::get('userid'):0;
        $article = Db::name('bbs')->where("id",$id)->where("isno",'0')->find();
        $uid = $article['userid'];
        $user = Db::name('user')->where("id",$uid)->find();
        $reply = Db::name('bbs')->join('user','bbs.userid=user.id')->where("reid",$id)->select();
        $this->assign('reply',$reply);
        $this->assign('uid',$uid);//文章所属用户
        $this->assign('nowuid',$nowuid);//当前登录用户
        $this->assign('article',$article);
        $this->assign('user',$user);
        return $this->fetch('bbs/detail');
    }
    public function delart(){
        $id = input('id');
        $res1 = db('bbs')->where("reid",$id)->delete();
        $res2 = db('bbs')->where("id",$id)->delete();
        if ($res2){
            $this->finish('删除成功');
        }else{
            $this->warning('删除失败');
        }
    }
    public function reply(){
        $this->checklogin();
        if(request()->isPost()) {
            $data=input('post.');
            $id = input('id');
            $userid = Session::get('userid');
            if (empty(input('content'))) {
                $this->warning('请输入回复内容');
            }else{
                $data1 = array(
                    'userid' => $userid,
                    'content' => $data['content'],
                    'reid'=>$id,
                    'addtime'=> date("Y-m-d H:i:s",time()),
                    'isno'=>1,
                );
//                print_r($data1);die;
                $ret = Db::name('bbs')->where("id",$id)->insert($data1);
                if (false === $ret) {
                    $this->warning('提交失败！');
                } else {
                    $this->finish('提交成功！');
                }
            }
        }else {
            $this->warning('请求类型错误，请通过合法途径请求！');
        }
    }
}