<?php
namespace app\admin\controller;
use think\Controller;
use think\Request;
use think\Db;
use app\admin\model\Shuju as ShujuModel;
use app\admin\model\Category as CategoryModel;
use app\admin\model\User as UserModel;
use think\Session;
class Shuju extends Base
{
    public function index()
    {
		$where = array();
		$name = array();
		$keywords1 = input('keywords1','');
		$keywords2 = input('keywords2','');
		$keywords3 = input('keywords3','');
		$keywords4 = input('keywords4','');
		$categoryid1 = input('categoryid1','');
		
		//$where['status'] = ['eq',0];
		$pid = input('pid','');
		if($pid>0||$pid){
			$where['pid'] = ['=',$pid];
			$name['pid'] = $pid;
			$this->assign('pid',$name['pid']);
		}
		if($keywords1){
			$where['title'] = ['like',"%".$keywords1."%"];
			$name['keywords1'] = $keywords1;
			$this->assign('keywords1',$name['keywords1']);
		}
		if($keywords2){
			$where['name'] = ['like',"%".$keywords2."%"];
			$name['keywords2'] = $keywords2;
			$this->assign('keywords2',$name['keywords2']);
		}
		if($keywords3){
			$where['name'] = ['like',"%".$keywords3."%"];
			$name['keywords3'] = $keywords3;
			$this->assign('keywords3',$name['keywords3']);
		}
		if($keywords4){
			$where['name'] = ['like',"%".$keywords4."%"];
			$name['keywords4'] = $keywords4;
			$this->assign('keywords4',$name['keywords4']);
		}
		if($categoryid1>0||$categoryid1){
			$where['categoryid'] = ['=',$categoryid1];
			$name['categoryid1'] = $categoryid1;
			$this->assign('categoryid1',$name['categoryid1']);
			
		}

		$order = array('id'=>'desc');
		$size = 10;
		$data = ShujuModel::where($where)
		->order($order)
		->paginate($size,false,array(
        	'query'=>$name
        ));
//		echo '<pre>';
//		print_r($data);
//        echo '</pre>';die;
		$count = ShujuModel::count();

		$this->assign('data',$data);
		$this->assign('count',$count);
		$this->assign('list',$this->get_category($pid));
		$this->assign('categoryid1',$categoryid1);
		return $this->fetch('shuju');
    }
	
	public function get_category(){
		return CategoryModel::field('*')->order('id asc')->select();
	}
    public function save(Request $request)
    {
        $data = $request->param();
        if(empty(trim($data['title']))){
            return ['code' => 1, 'msg' => '名称不能为空！'];
        }
		if(Session::get('userid')){
		$data['userid']=Session::get('userid');
		$data['zhuangtai']="未审核";
		}
        $result = ShujuModel::insert($data);
        if($result){
            return ['code' => 0, 'msg' => '添加成功！'];
        }else{
            return ['code' => 1, 'msg' => '添加失败！'];
        }

    }
    public function read(Request $request)
    {
        $data = $request->param();
        $shujuModel = new ShujuModel();
        $rs = $shujuModel->find($data['id']);
        $this->assign('rs',$rs);
		$this->assign('list',$this->get_category(1));
		$this->assign('categoryid',$categoryid);
		$this->assign('list1',$this->get_category(2));
		$this->assign('categoryid2',$categoryid2);
    }
    public function edit(Request $request)
    {
        $data = $request->param();
        $ShujuModel = new ShujuModel();
        $rs = $ShujuModel->find($data['id']);
        return ['code' => 1, 'msg' => '','data' => $rs];
		$this->assign('list',$this->get_category(1));
		$this->assign('categoryid',$categoryid);
		$this->assign('list1',$this->get_category(2));
		$this->assign('categoryid2',$categoryid2);
		$this->assign('list3',$this->get_category(3));
		$this->assign('categoryid3',$categoryid3);
        return $this->fetch();
    }
    public function update(Request $request)
    {
            $data = $request->param();
            $result = $this->validate($data, 'Shuju.update');
            if (true !== $result) {
                return ['code' => 1, 'msg' => $result];
            } else {
                $where = array('id'=>$data['id']);
                $ret = ShujuModel::where($where)->update($data);
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
				$this->getDeleInfo('shuju',array(),$id['data']);
				for ($i=0; $i<count($id['data']); $i++){
					$res = Db::table('ordersta')->where('shujuid',$id['data'][$i])->delete();
					$res = Db::table('comment')->where('shujuid',$id['data'][$i])->delete();
					$res = Db::table('shoucang')->where('shujuid',$id['data'][$i])->delete();
				}
			}else{
				$this->getDeleInfo('shuju',array(),$id);
				$res = Db::table('ordersta')->where('shujuid',$id)->delete();
				$res = Db::table('comment')->where('shujuid',$id)->delete();
				$res = Db::table('shoucang')->where('shujuid',$id)->delete();
			}
		}
	}
	
	public function editAll(Request $request){
		$id = $request->param();
		if(!is_null($id)){
			$model = model("shuju");
			$ini['id'] = array('in',$id['data']);
			$result = $model->where($ini)->setField('zhuangtai','zhuangtai');
		}
	}
    
	public function index1()
    {
		
		$userid = Session::get('userid');
		$where = array();
		$name = array();
		$keywords1 = input('keywords1','');
		$keywords2 = input('keywords2','');
		$keywords3 = input('keywords3','');
		$keywords4 = input('keywords4','');
		$categoryid1 = input('categoryid1','');
		$where['userid'] = ['eq',$userid];
		$where['status'] = ['eq',0];
		$pid = input('pid','');
		if($pid>0||$pid){
			$where['pid'] = ['=',$pid];
			$name['pid'] = $pid;
			$this->assign('pid',$name['pid']);
		}
		if($keywords1){
			$where['title'] = ['like',"%".$keywords1."%"];
			$name['keywords1'] = $keywords1;
			$this->assign('keywords1',$name['keywords1']);
		}
		if($keywords2){
			$where['name'] = ['like',"%".$keywords2."%"];
			$name['keywords2'] = $keywords2;
			$this->assign('keywords2',$name['keywords2']);
		}
		if($keywords3){
			$where['name'] = ['like',"%".$keywords3."%"];
			$name['keywords3'] = $keywords3;
			$this->assign('keywords3',$name['keywords3']);
		}
		if($keywords4){
			$where['name'] = ['like',"%".$keywords4."%"];
			$name['keywords4'] = $keywords4;
			$this->assign('keywords4',$name['keywords4']);
		}
		if($categoryid1>0||$categoryid1){
			$where['categoryid'] = ['=',$categoryid1];
			$name['categoryid1'] = $categoryid1;
			$this->assign('categoryid1',$name['categoryid1']);
			
		}
		$categoryid2 = input('categoryid2','');
		if($categoryid2>0||$categoryid2){
			$where['categoryid1'] = ['=',$categoryid2];
			$name['categoryid2'] = $categoryid2;
			$this->assign('categoryid2',$name['categoryid2']);
			
		}
		$categoryid3 = input('categoryid3','');
		if($categoryid3>0||$categoryid2){
			$where['categoryid3'] = ['=',$categoryid3];
			$name['categoryid3'] = $categoryid3;
			$this->assign('categoryid3',$name['categoryid3']);
			
		}
		$order = array('id'=>'desc');
		$size = 10;
		$data = ShujuModel::where($where)
		->order($order)
		->paginate($size,false,array(
        	'query'=>$name
        ));
		$count = ShujuModel::count();

		$this->assign('data',$data);
		$this->assign('count',$count);
		return $this->fetch('shuju1');
    }
	public function delete(Request $request)
    {
		$data = $request->param();
		$ShujuModel = new ShujuModel();
		$ret = $ShujuModel->allowField(true)->update(['status' => $data['status']],
		['id' => $data['id']]);
		if (false === $ret) {
			return ['code' => 1, 'msg' => '操作失败!执行如下操作：' . $CategoryModel->getLastSql()];
		} else {
			return ['code' => 0, 'msg' => '操作成功!'];
		}
	}
	
}
