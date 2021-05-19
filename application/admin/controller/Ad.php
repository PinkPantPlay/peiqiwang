<?php


namespace app\admin\controller;
use think\Controller;
use think\Request;
use think\Db;
use app\admin\model\Ad as AdModel;
use app\admin\model\Category as CategoryModel;
use app\admin\model\User as UserModel;
use think\Session;
class Ad extends Base
{
    public function index()
    {
        $where = array();
        $name = array();
        $keywords1 = input('keywords1','');

        if($keywords1){
            $where['title'] = ['like',"%".$keywords1."%"];
            $name['keywords1'] = $keywords1;
            $this->assign('keywords1',$name['keywords1']);
        }


        $order = array('id'=>'desc');
//        print_r($order);die;
        $size = 5;
        $data = AdModel::where($where)
            ->order($order)
            ->paginate($size,false,array(
                'query'=>$name
            ));
//        echo '<pre>';
//        print_r($data);
//        echo '</pre>';die;
        $count = AdModel::count();
        $this->assign('data',$data);
        $this->assign('count',$count);
        return $this->fetch('ad_list');
    }
    //banner编辑页
    public function edit($id){
        $data=Db::name('ad')->where('id',$id)->find();
        $sid=$data['sid'];
        $shuju=Db::name('shuju')->where("id",$sid)->find();
        $res = Db::name('shuju')->select();
        $this->assign('data',$data);
        $this->assign('shuju',$shuju);
        $this->assign('res',$res);
        return $this->fetch();
    }
    //搜索商品
    public function searchsid(Request $request){
        $data = $request->param('stitle');
        $res = Db::name('shuju')->where("title",'like',"%{$data}%")->order("addtime")->select();
        if ($res===0){
            echo 0;
        }else{
            return $res;
        }
    }
    //banner编辑处理
    public function updateBanner(Request $request)
    {
        if ($request->isAjax()) {
            $data = $request->param();
            $result = $this->validate($data, 'Ad.updateBanner');
            if (true !== $result) {
                return ['code' => 1, 'msg' => $result];
            } else {
                $adModel = new AdModel();
                $updatetime = time();
                $ret = $adModel->allowField(true)->update([
                    'title' => $data['title'],
                    'path'=>$data['path'],
                    'sid'=>$data['sid'],
                    'updatetime'=>$updatetime,
                ], ['id' => $data['id']]);
                if (!!!$ret) {
                    return ['code' => 1, 'msg' => '修改失败！执行如下操作：' . $adModel->getLastSql()];
                } else {
                    return ['code' => 0, 'msg' => '修改成功！'];
                }
            }
        } else {
            $this->error('请求类型错误，请通过合法途径请求！');
        }
    }
}