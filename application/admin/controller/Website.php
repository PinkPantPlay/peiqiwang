<?php


namespace app\admin\controller;


use think\Controller;
use think\Db;
use think\Request;
use think\Validate;

class Website extends Controller
{
    public function webinfo(){
        $web = Db::name('website')->find();
        $this->assign('web',$web);
        return $this->fetch('website/website');
    }
    public function save(Request $request){
        if ($request->isAjax()){
            $data = $request->param();
            $result = $this->validate($data,'website.save');
            if ($result != true){
                return ['code' => 1, 'msg' => $result];
            }else{
                $res = Db::table('website')->update(['title' => $data['title'],'address' => $data['address'],'keyword' => $data['keyword'],'id'=>1]);
                if ($res){
                    return ['code' => 0, 'msg' => '修改成功'];
                }else{
                    return ['code' => 1, 'msg' => '数据执行有误，请重新修改'];
                }
            }
        } else {
            $this->error('请求类型错误，请通过合法途径请求！');
        }

    }
}