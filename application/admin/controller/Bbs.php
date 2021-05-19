<?php
namespace app\admin\controller;
use think\Controller;
use think\Request;
use app\admin\model\Bbs as BbsModel;
use app\admin\model\Bbstype as BbstypeModel;

class Bbs extends Base
{
    public function index()
    {
		$where = array();
		$name = array();
		$kewords1 = input('kewords1','');
		$kewords2 = input('kewords2','');
		$kewords3 = input('kewords3','');
		$kewords4 = input('kewords4','');
		$categoryid = input('categoryid','');
		$where['status'] = ['eq',0];
		$where['isno'] = ['eq',0];
		if($kewords1){
			$where['title'] = ['like',"%".$kewords1."%"];
			$name['kewords1'] = $kewords1;
			$this->assign('kewords1',$name['kewords1']);
		}
		if($kewords2){
			$where['content'] = ['like',"%".$kewords2."%"];
			$name['kewords2'] = $kewords2;
			$this->assign('kewords2',$name['kewords2']);
		}
		if($kewords3){
			$where['username'] = ['like',"%".$kewords3."%"];
			$name['kewords3'] = $kewords3;
			$this->assign('kewords3',$name['kewords3']);
		}
		if($kewords4){
			$where['name'] = ['like',"%".$kewords4."%"];
			$name['kewords4'] = $kewords4;
			$this->assign('kewords4',$name['kewords4']);
		}
		if($categoryid>0||$categoryid){
			$where['categoryid'] = ['=',$categoryid];
			$name['categoryid'] = $categoryid;
			$this->assign('categoryid',$name['categoryid']);
			
		}
		$order = array('id'=>'desc');
		$size = 8;
		$data = BbsModel::where($where)
		->order($order)
		->paginate($size,false,array(
        	'query'=>$name
        ));
		$count = BbsModel::count();

		$this->assign('data',$data);
		$this->assign('count',$count);
		return $this->fetch('bbs');
    }
	
	public function index1()
    {
		$where = array();
		$name = array();
		$kewords1 = input('kewords1','');
		$kewords2 = input('kewords2','');
		$kewords3 = input('kewords3','');
		$kewords4 = input('kewords4','');
		$categoryid = input('categoryid','');
		$where['status'] = ['eq',0];
		$where['isno'] = ['eq',1];
		if($kewords1){
			$where['title'] = ['like',"%".$kewords1."%"];
			$name['kewords1'] = $kewords1;
			$this->assign('kewords1',$name['kewords1']);
		}
		if($kewords2){
			$where['content'] = ['like',"%".$kewords2."%"];
			$name['kewords2'] = $kewords2;
			$this->assign('kewords2',$name['kewords2']);
		}
		if($kewords3){
			$where['username'] = ['like',"%".$kewords3."%"];
			$name['kewords3'] = $kewords3;
			$this->assign('kewords3',$name['kewords3']);
		}
		if($kewords4){
			$where['name'] = ['like',"%".$kewords4."%"];
			$name['kewords4'] = $kewords4;
			$this->assign('kewords4',$name['kewords4']);
		}
		if($categoryid>0||$categoryid){
			$where['categoryid'] = ['=',$categoryid];
			$name['categoryid'] = $categoryid;
			$this->assign('categoryid',$name['categoryid']);
			
		}
		$order = array('id'=>'desc');
		$size = 8;
		$data = BbsModel::where($where)
		->order($order)
		->paginate($size,false,array(
        	'query'=>$name
        ));
		$count = BbsModel::count();
		$this->assign('data',$data);
		$this->assign('count',$count);
		return $this->fetch('bbs1');
    }
	public function bbstype(){
	    $bbstype = db('bbstype')->paginate(5);
	    $this->assign('bbstype',$bbstype);
	    return $this->fetch('bbs/bbstype');
	}
	public function bbstypeedit(Request $request)
    {
        $data = $request->param();
        $rs = db('bbstype')->find($data['id']);
        return ['code' => 1, 'msg' => '','data' => $rs];
        return $this->fetch();
    }
    public function bbstypeupdate(Request $request){
        $data = $request->param();
        if(empty(trim($data['title']))){
            return ['code' => 1, 'msg' => '名称不能为空！'];
        }
        $result = db('bbstype')->where('id',$data['id'])->update(['title'=>$data['title'],'status'=>0]);
        if($result){
            return ['code' => 0, 'msg' => '添加成功！'];
        }else{
            return ['code' => 1, 'msg' => '添加失败！'];
        }
    }
    public function delbbsAll(Request $request){
		$id = $request->param();
		if(!is_null($id)){
			if(is_array($id)){
				$this->getDeleInfo('bbstype',array(),$id['data']);
			}else{
				$this->getDeleInfo('bbstype',array(),$id);
			}
		}
	}
	public function get_category($pid){
		return CategoryModel::field('*')->where('pid='.$pid)->select();
	}

    public function save(Request $request)
    {
        $data = $request->param();
        if(empty(trim($data['title']))){
            return ['code' => 1, 'msg' => '名称不能为空！'];
        }
        $result = db('bbstype')->insert(['title'=>$data['title'],'status'=>0]);
        if($result){
            return ['code' => 0, 'msg' => '添加成功！'];
        }else{
            return ['code' => 1, 'msg' => '添加失败！'];
        }
    }
    public function read(Request $request)
    {
        $data = $request->param();
        $bbsModel = new BbsModel();
        $rs = $bbsModel->find($data['id']);
        $this->assign('rs',$rs);
		$this->assign('list',$this->get_category());
		$this->assign('categoryid',$categoryid);
    }
    public function edit(Request $request)
    {
        $data = $request->param();
        $BbsModel = new BbsModel();
        $rs = $BbsModel->find($data['id']);
        return ['code' => 1, 'msg' => '','data' => $rs];
		$this->assign('list',$this->get_category());
		$this->assign('categoryid',$categoryid);
        return $this->fetch();
    }
    public function update(Request $request)
    {
            $data = $request->param();
            $result = $this->validate($data, 'Bbs.update');
            if (true !== $result) {
                return ['code' => 1, 'msg' => $result];
            } else {
                $where = array('id'=>$data['id']);
                $ret = BbsModel::where($where)->update($data);
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
				$this->getDeleInfo('bbs',array(),$id['data']);
			}else{
				$this->getDeleInfo('bbs',array(),$id);
			}
		}
	}
	
	public function editAll(Request $request){
		$id = $request->param();
		if(!is_null($id)){
			$model = model("bbs");
			$ini['id'] = array('in',$id['data']);
			$result = $model->where($ini)->setField('bbs_status',-1);
		}
	}
    
    public function delete(Request $request)
    {
		$data = $request->param();
		$bbsModel = new BbsModel();
		$ret = $bbsModel->allowField(true)->update(['status' => -1],
		['id' => $data['id']]);
		if (false === $ret) {
			return ['code' => 1, 'msg' => '操作失败!执行如下操作：' . $bbsModel->getLastSql()];
		} else {
			return ['code' => 0, 'msg' => '操作成功!'];
		}
	}
}
