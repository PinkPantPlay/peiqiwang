<?php
namespace app\admin\controller;
use think\Controller;
use think\Request;
use think\Db;
use app\admin\model\About as AboutModel;
use app\admin\model\Category as CategoryModel;
use app\admin\model\User as UserModel;
use think\Session;
class About extends Base
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
		$size = 10;
		$data = AboutModel::where($where)
		->order($order)
		->paginate($size,false,array(
        	'query'=>$name
        ));
		$count = AboutModel::count();

		$this->assign('data',$data);
		$this->assign('count',$count);
		return $this->fetch('about');
    }
	
    public function save(Request $request)
    {
        $data = $request->param();
        if(empty(trim($data['title']))){
            return ['code' => 1, 'msg' => '名称不能为空！'];
        }
		if(Session::get('userid')){
		$data['userid']=Session::get('userid');
		}
        $result = AboutModel::insert($data);
        if($result){
            return ['code' => 0, 'msg' => '添加成功！'];
        }else{
            return ['code' => 1, 'msg' => '添加失败！'];
        }

    }
    public function read(Request $request)
    {
        $data = $request->param();
        $aboutModel = new AboutModel();
        $rs = $aboutModel->find($data['id']);
        $this->assign('rs',$rs);

    }
    public function edit(Request $request)
    {
        $data = $request->param();
        $AboutModel = new AboutModel();
        $rs = $AboutModel->find($data['id']);
        return ['code' => 1, 'msg' => '','data' => $rs];
        return $this->fetch();
    }
    public function update(Request $request)
    {
            $data = $request->param();
            $result = $this->validate($data, 'About.update');
            if (true !== $result) {
                return ['code' => 1, 'msg' => $result];
            } else {
                $where = array('id'=>$data['id']);
                $ret = AboutModel::where($where)->update($data);
                if (!$ret) {
                    return ['code' => 1, 'msg' => '修改失败！执行如下操作：'];
                } else {
                    return ['code' => 0, 'msg' => '修改成功！'];
                }
            
        }
    }
	
}
