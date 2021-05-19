<?php

namespace app\admin\controller;
use think\Controller;
use think\Request;
use app\admin\model\Category as CategoryModel;
class Category extends Base
{
    public function index()
    {
		$where = array();
        $name = array();
		
		$kewords1 = input('kewords1','');
		$where['status'] = ['eq',0];
		$pid = input('pid','');
		if($pid>0||$pid){
			$where['pid'] = ['=',$pid];
			$name['pid'] = $pid;
			$this->assign('pid',$name['pid']);
		}
		if($kewords1){
			$where['title'] = ['like',"%".$kewords1."%"];
			$name['kewords1'] = $kewords1;
			$this->assign('kewords1',$name['kewords1']);
		}
		$order = array('id'=>'asc');
		$size = 10;
		$data = CategoryModel::where($where)
		->order($order)
		->paginate($size,false,array(
        	'query'=>$name
        ));
		$count = CategoryModel::count();

		$this->assign('data',$data);
		$this->assign('count',$count);
		return $this->fetch();
    }
	
    public function save(Request $request)
    {
        $data = $request->param();
        /*if(empty(trim($data['title']))){
            return ['code' => 1, 'msg' => '名称不能为空！'];
        }*/
        $result = CategoryModel::insert($data);
        if($result){
            return ['code' => 0, 'msg' => '添加成功！'];
        }else{
            return ['code' => 1, 'msg' => '添加失败！'];
        }

    }
    public function read(Request $request)
    {
        $data = $request->param();
        $categoryModel = new CategoryModel();
        $category = $categoryModel->find($data['id']);
        return ['code' => 1, 'msg' => '','data' => $category];
    }
    public function edit(Request $request)
    {
        $data = $request->param();
        $CategoryModel = new CategoryModel();
        $rs = $CategoryModel->find($data['id']);
        return ['code' => 1, 'msg' => '','data' => $rs];
        return $this->fetch();
    }
    public function update(Request $request)
    {
            $data = $request->param();
            $result = $this->validate($data, 'Category.update');
            if (true !== $result) {
                return ['code' => 1, 'msg' => $result];
            } else {
                $where = array('id'=>$data['id']);
                $ret = CategoryModel::where($where)->update($data);
                if (!$ret) {
                    return ['code' => 1, 'msg' => '修改失败！执行如下操作：'];
                } else {
                    return ['code' => 0, 'msg' => '修改成功！'];
                }
            
        }
    }
	public function delAll(Request $request){
		$id = $request->param();
		if(!is_null($id)){
			if(is_array($id)){
				$this->getDeleInfo('category',array(),$id['data']);
				for ($i=0; $i<count($id['data']); $i++){
					$res = Db::table('shuju')->where('categoryid',$id['data'][$i])->delete();
					$res = Db::table('ordersta')->where('categoryid',$id['data'][$i])->delete();
					$res = Db::table('comment')->where('categoryid',$id['data'][$i])->delete();
					$res = Db::table('content1')->where('categoryid',$id['data'][$i])->delete();
				}
			}else{
				$this->getDeleInfo('category',array(),$id);
				$res = Db::table('shuju')->where('categoryid',$id)->delete();
				$res = Db::table('ordersta')->where('categoryid',$id)->delete();
				$res = Db::table('comment')->where('categoryid',$id)->delete();
				$res = Db::table('content1')->where('categoryid',$id)->delete();
			}
		}
	}
	
	public function editAll(Request $request){
		$id = $request->param();
		if(!is_null($id)){
			if(is_array($id)){
				$this->getEditInfo('category',array(),$id['data']);
			}else{
				$this->getEditInfo('category',array(),$id);
			}
		}
	}
    public function delete(Request $request)
    {
		$data = $request->param();
		$CategoryModel = new CategoryModel();
		$ret = $CategoryModel->allowField(true)->update(['status' => -1],
		['id' => $data['id']]);
		if (false === $ret) {
			return ['code' => 1, 'msg' => '操作失败!执行如下操作：' . $CategoryModel->getLastSql()];
		} else {
			return ['code' => 0, 'msg' => '操作成功!'];
		}
	}
}
