<?php
namespace app\admin\controller;
use think\Controller;
use think\Request;
use think\Db;
use app\admin\model\Orders as OrdersModel;
use app\admin\model\Ordersta as OrderstaModel;
use app\admin\model\User as UserModel;
use think\Session;


class Orders extends Base
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
		$where['status'] = ['neq',-1];
		if($kewords1){
			$where['title'] = ['like',"%".$kewords1."%"];
			$name['kewords1'] = $kewords1;
			$this->assign('kewords1',$name['kewords1']);
		}
		if($kewords2){
			$where['onumber'] = ['like',"%".$kewords2."%"];
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
		$data = OrdersModel::where($where)
		->order($order)
		->paginate($size,false,array(
        	'query'=>$name
        ));
		$count = OrdersModel::count();

		$this->assign('data',$data);
		$this->assign('count',$count);
		return $this->fetch('orders');
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
        $result = OrdersModel::insert($data);
        if($result){
            return ['code' => 0, 'msg' => '添加成功！'];
        }else{
            return ['code' => 1, 'msg' => '添加失败！'];
        }

    }
    public function read(Request $request)
    {
        $data = $request->param();
        $OrdersModel = new OrdersModel();
        $orders = $OrdersModel->find($data['id']);
        $this->assign('orders', $orders);
		$where = array();
		$name = array();
		
		$where['ordersid'] = ['=',$data['id']];
		$name['ordersid'] = $data['id'];
		$order = array('id'=>'desc');
		$size = 10;
		$data = OrderstaModel::where($where)
		->order($order)
		->paginate($size,false,array(
        	'query'=>$name
        ));
		$count = OrderstaModel::count();
		
		$this->assign('data',$data);
		$this->assign('count',$count);
        return $this->fetch();
    }
    public function edit(Request $request)
    {
        $data = $request->param();
        $OrdersModel = new OrdersModel();
        $rs = $OrdersModel->find($data['id']);
        return ['code' => 1, 'msg' => '','data' => $rs];
		$this->assign('list',$this->get_category());
		$this->assign('categoryid',$categoryid);
        return $this->fetch();
    }

	public function delAll(Request $request){
		$id = $request->param();
		if(!is_null($id)){
			if(is_array($id)){
				$this->getDeleInfo('orders',array(),$id['data']);
			}else{
				$this->getDeleInfo('orders',array(),$id);
			}
		}
	}
	
	public function editAll(Request $request){
		$id = $request->param();
		if(!is_null($id)){
			$model = model("orders");
			$ini['id'] = array('in',$id['data']);
			$result = $model->where($ini)->setField('zhuangtai','已审核');
		}
	}
    public function cancelAll(Request $request){
        $id = $request->param();
        if (!is_null($id)){
            $model = model("orders");
            $ini['id'] = array('in',$id['data']);
            $result = $model->where("zhuangtai","申请取消")->where($ini)->setField('zhuangtai','已取消');
        }
    }
    public function delete(Request $request)
    {
		$data = $request->param();
		$OrdersModel = new OrdersModel();
		$ret = $OrdersModel->allowField(true)->update(['status' => -1],
		['id' => $data['id']]);
		if (false === $ret) {
			return ['code' => 1, 'msg' => '操作失败!执行如下操作：' . $OrdersModel->getLastSql()];
		} else {
			return ['code' => 0, 'msg' => '操作成功!'];
		}
	}
	
	
	
	public function ordersuser()
    {
		$where = array();
		$name = array();
		$kewords1 = input('kewords1','');
		$kewords2 = input('kewords2','');
		$kewords3 = input('kewords3','');
		$kewords4 = input('kewords4','');
		$categoryid = input('categoryid','');
		$userid = Session::get('userid');
		$where['userid'] = ['eq',$userid];
		$where['status'] = ['eq',0];
		if($kewords1){
			$where['title'] = ['like',"%".$kewords1."%"];
			$name['kewords1'] = $kewords1;
			$this->assign('kewords1',$name['kewords1']);
		}
		if($kewords2){
			$where['onumber'] = ['like',"%".$kewords2."%"];
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
		$data = OrdersModel::where($where)
		->order($order)
		->paginate($size,false,array(
        	'query'=>$name
        ));
		$count = OrdersModel::count();

		$this->assign('data',$data);
		$this->assign('count',$count);
		return $this->fetch();
    }
	public function readuser(Request $request)
    {
        $data = $request->param();
        $OrdersModel = new OrdersModel();
        $orders = $OrdersModel->find($data['id']);
        $this->assign('orders', $orders);
		$where = array();
		$name = array();
		
		$where['ordersid'] = ['=',$data['id']];
		$name['ordersid'] = $data['id'];
		$order = array('id'=>'desc');
		$size = 10;
		$data = OrderstaModel::where($where)
		->order($order)
		->paginate($size,false,array(
        	'query'=>$name
        ));
		$count = OrderstaModel::count();
		
		$this->assign('data',$data);
		$this->assign('count',$count);
        return $this->fetch();
    }
	public function fahuocreate(Request $request)
    {
       
		$data = $request->param();
        $order = db('orders')->find($data['ordersid']);
        $this->assign('orders', $order);
        return $this->fetch();
    }

	public function shouhuo(Request $request)
    {
        $data = $request->param();
		$res = Db::name('orders')->update(['zhuangtai'=>'已收货','id'=>$data['id']]);
		$res = Db::name('ordersta')->where('ordersid',$data['id'])->update(['zhuangtai'=>'已收货']);
		$this->success('操作成功！');
	
    }
	
	
	public function update(Request $request)
    {
        $data = $request->param();
		$where = array('id'=>$data['id']);
        $result = OrdersModel::where($where)->update($data);
       	$res = Db::name('ordersta')->where('ordersid',$data['id'])->update(['zhuangtai'=>'已发货']);
        if($result){
            return ['code' => 0, 'msg' => '操作成功！'];
        }else{
            return ['code' => 1, 'msg' => '操作失败！'];
        }
    }
	
}
