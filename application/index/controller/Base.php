<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use app\index\model\User as UserModel;
use think\Session;
class Base extends Controller
{
    public function _initialize(){
        $this->getWebsite();
        $this->getNavMenus();
        $this->getleft();
        $this->getNav();
    }
    // //构造函数
    // public function __construct()
    // {
    //     //继承父类
    //     parent::__construct();
    // }
    public function checklogin()
    {
        $userid = Session::get('userid');
        if (empty($userid)) {
            $this->error('请登录！','index/login/login');
        }else{
            $userArr = Db::name('user')->where(['id' => $userid])->select();
            $user = $userArr[0];
            $this->assign('user', $user);
        }
        return view();
    }
    public function getWebsite(){
        $website = db('website')->find(1);
        $this->assign('website',$website);
    }
    public function getNavMenus(){
        $menures=db('menu')->where(array('pid'=>0))->where(array('type'=>1))->order('grade asc')->select();
        $this->assign('menures',$menures);
    }
    public function getleft(){
        $category = db('category')->where(array('status'=>0))->where(array('pid'=>1))->order('id asc')->limit(9)->select();
        $pid = input('pid','');
        if($pid){
            $where['pid'] = ['=',$pid];
            $name['pid'] = $pid;
            $this->assign('pid',$name['pid']);
            $shujuleft = db('shuju')->where($where)->where(array('status'=>0))->order('apv desc')->limit(5)->select();
            $shujuleft1 = db('shuju')->where($where)->where(array('status'=>0))->order('id desc')->limit(5)->select();
        }else{
            $shujuleft = db('shuju')->where(array('status'=>0))->order('apv desc')->limit(5)->select();
            $shujuleft1 = db('shuju')->where(array('status'=>0))->order('id desc')->limit(5)->select();
        }
        $this->assign(array(
            'shujuleft'=>$shujuleft,
            'shujuleft1'=>$shujuleft1,
            'category'=>$category,
        ));
    }
    /**
     * 操作成功跳转的快捷方法
     * @access protected
     * @param mixed  $msg    提示信息
     * @param mixed  $data   返回的数据
     * @param mixed  $code   返回的状态码
     * @return void
     */
    public function finish($msg = '未知消息', $data = null, $code = 1)
    {
        return $this->back($msg, $data, $code);
    }

    /**
     * 操作失败跳转的快捷方法
     * @access protected
     * @param mixed  $msg    提示信息
     * @param mixed  $data   返回的数据
     * @param mixed  $code   返回的状态码
     * @return void
     */
    public function warning($msg = '未知错误', $data = null, $code = 0)
    {
        return $this->back($msg, $data, $code);
    }

    /**
     * 返回数据的方法
     * @access protected
     * @param mixed  $msg    提示信息
     * @param mixed  $data   返回的数据
     * @param mixed  $code   返回的状态码
     * @return void
     */
    public function back($msg = '未知消息', $data = null, $code = 1)
    {

        if(empty($msg))
        {
            $msg = '未知消息';
        }

        $result = [
            'msg'=>$msg, //提示信息
            'data'=>$data,  //返回的数据
            'code'=>$code  //状态码
        ];

        //返回接口数据了
        echo json_encode($result);
        exit;
    }

    private function getNav()
    {
        $nav = db('menu')->where("auth",2)->select();
        $this->assign('nav',$nav);
    }
}