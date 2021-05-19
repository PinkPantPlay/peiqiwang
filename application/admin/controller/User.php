<?php
namespace app\admin\controller;
use think\Controller;
use think\Request;
use app\admin\model\User as UserModel;
use think\Session;

class User extends Base
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
// 		$where['status'] = ['eq',0];
		$where['type'] = ['like',"%用户%"];
		if($kewords1){
			$where['username'] = ['like',"%".$kewords1."%"];
			$name['kewords1'] = $kewords1;
			$this->assign('kewords1',$name['kewords1']);
		}
		if($kewords2){
			$where['nickname'] = ['like',"%".$kewords2."%"];
			$name['kewords2'] = $kewords2;
			$this->assign('kewords2',$name['kewords2']);
		}
		if($kewords3){
			$where['name'] = ['like',"%".$kewords3."%"];
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
		$data = UserModel::where($where)
		->order($order)
		->paginate($size,false,array(
        	'query'=>$name
        ));
		$count = UserModel::count();

		$this->assign('data',$data);
		$this->assign('count',$count);
		return $this->fetch('user');
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
		$where['type'] = ['like',"%房东%"];
		if($kewords1){
			$where['username'] = ['like',"%".$kewords1."%"];
			$name['kewords1'] = $kewords1;
			$this->assign('kewords1',$name['kewords1']);
		}
		if($kewords2){
			$where['nickname'] = ['like',"%".$kewords2."%"];
			$name['kewords2'] = $kewords2;
			$this->assign('kewords2',$name['kewords2']);
		}
		if($kewords3){
			$where['name'] = ['like',"%".$kewords3."%"];
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
		$data = UserModel::where($where)
		->order($order)
		->paginate($size,false,array(
        	'query'=>$name
        ));
		$count = UserModel::count();

		$this->assign('data',$data);
		$this->assign('count',$count);
		return $this->fetch('user1');
    }

    public function save(Request $request)
    {
        $data = $request->param();
		$result = $this->validate($data, 'User.save');
		$data['password'] = md5(trim($data['password']));
		if (true !== $result) {
			return ['code' => 1, 'msg' => $result];
        }else {
			$ret = UserModel::insert($data);
			if($ret){
				return ['code' => 0, 'msg' => '添加成功！'];
			}else{
				return ['code' => 1, 'msg' => '添加失败！'];
			}
		}
    }
    public function read(Request $request)
    {
        $data = $request->param();
        $userModel = new UserModel();
        $rs = $userModel->find($data['id']);
        $this->assign('rs',$rs);
    }
    public function edit(Request $request)
    {
        $data = $request->param();
        $UserModel = new UserModel();
        $rs = $UserModel->find($data['id']);
        return ['code' => 1, 'msg' => '','data' => $rs];
        return $this->fetch('user');
    }
    public function editstatus(Request $request){
        $data = $request->param();
        $res = db('user')->where("id",$data['id'])->update(['status' => $data['status']]);
        if (!$res) {
            return ['code' => 1, 'msg' => '修改失败！'];
        } else {
            return ['code' => 0, 'msg' => '修改成功！'];
        }
    }
    public function update(Request $request)
    {
            $data = $request->param();
            $result = $this->validate($data, 'User.update');
			if($data['password']){
				$data['password'] = md5(trim($data['password']));}else{
					$UserModel = new UserModel();
        			$rs = $UserModel->find($data['id']);
					$data['password'] = $rs['password'];
			}
            if (true !== $result) {
                return ['code' => 1, 'msg' => $result];
            } else {
                $where = array('id'=>$data['id']);
                $ret = UserModel::where($where)->update($data);
                if (!$ret) {
                    return ['code' => 1, 'msg' => '修改失败！执行如下操作：'];
                } else {
                    return ['code' => 0, 'msg' => '修改成功！'];
                }
            
        }
    }
	public function edit_self(Request $request)
    {
        $userid = Session::get('userid');
        $UserModel = new UserModel();
        $rs = $UserModel->where(['id' => $userid])->find();
        $this->assign('rs', $rs);
		
        return $this->fetch();
    }
	public function update_self(Request $request)
    {
        if ($request->isAjax()) {
            $data = $request->param();
            $result = $this->validate($data, 'User.update_self');
            if (true !== $result) {
                return ['code' => 1, 'msg' => $result];
            } else {
                $userModel = new UserModel();
                $ret = $userModel->allowField(true)->update([
                    'nickname' => $data['nickname'],
                    'sex' => $data['sex'],
                    'img' => $data['img'],
                    'tel' => $data['tel'],
                    'address' => $data['address']
                ], ['id' => $data['id']]);
                if (!!!$ret) {
                    return ['code' => 1, 'msg' => '修改失败！执行如下操作：' . $userModel->getLastSql()];
                } else {
                    return ['code' => 0, 'msg' => '修改成功！'];
                }
            }
        } else {
            $this->error('请求类型错误，请通过合法途径请求！');
        }
    }
	
	
	public function edit_self1(Request $request)
    {
        $userid = Session::get('userid');
        $UserModel = new UserModel();
        $rs = $UserModel->where(['id' => $userid])->find();
        $this->assign('rs', $rs);
        return $this->fetch();
    }
	public function update_self1(Request $request)
    {
        
		$data = $request->param();
            $result = $this->validate($data, 'User.update');
            if (true !== $result) {
                return ['code' => 1, 'msg' => $result];
            } else {
                $where = array('id'=>$data['id']);
                $ret = UserModel::where($where)->update($data);
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
				$this->getDeleInfo('user',array(),$id['data']);
				for ($i=0; $i<count($id['data']); $i++){
					$res = Db::table('shuju')->where('userid',$id['data'][$i])->delete();
					$res = Db::table('ordersta')->where('userid',$id['data'][$i])->delete();
					$res = Db::table('comment')->where('userid',$id['data'][$i])->delete();
					$res = Db::table('content1')->where('userid',$id['data'][$i])->delete();
					$res = Db::table('shoucang')->where('userid',$id['data'][$i])->delete();
					$res = Db::table('bbs')->where('userid',$id['data'][$i])->delete();
				}
			}else{
				$this->getDeleInfo('user',array(),$id);
				$res = Db::table('shuju')->where('userid',$id)->delete();
				$res = Db::table('ordersta')->where('userid',$id)->delete();
				$res = Db::table('comment')->where('userid',$id)->delete();
				$res = Db::table('content1')->where('userid',$id)->delete();
				$res = Db::table('shoucang')->where('userid',$id)->delete();
				$res = Db::table('bbs')->where('userid',$id)->delete();
			}
		}
	}
	
	public function editAll(Request $request){
		$id = $request->param();
		if(!is_null($id)){
			$model = model("user");
			$ini['id'] = array('in',$id['data']);
			$result = $model->where($ini)->setField('user_status',-1);
		}
	}
    
    public function delete(Request $request)
    {
		$data = $request->param();
		$userModel = new UserModel();
		$ret = $userModel->allowField(true)->update(['status' => -1],
		['id' => $data['id']]);
		if (false === $ret) {
			return ['code' => 1, 'msg' => '操作失败!执行如下操作：' . $userModel->getLastSql()];
		} else {
			return ['code' => 0, 'msg' => '操作成功!'];
		}
	}
}
