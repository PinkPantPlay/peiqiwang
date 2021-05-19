<?php

namespace app\admin\controller;
use think\Controller;
use think\Request;
use app\admin\model\Admin as AdminModel;
use think\Session;



class Admin extends Controller
{
    
    public function index(Request $request)
    {
        $data = $request->param();
		$data 	= Request::instance()->get();
    	$where 	= [];
    	empty($data['uname'])	|| $where['uname'] = ['like','%'.$data['uname'].'%' ];
		empty($data['tel'])	|| $where['tel'] = ['like','%'.$data['tel'].'%' ];
		//print_r($where);die;
		$adminModel = new AdminModel();
        $adminList = $adminModel->where("type='管理员'")->where($where)->order('id desc')->paginate(10,false,['query'=>request()->param()]); 
        $this->assign('admin_list', $adminList);
        return $this->fetch();
    }
    
    public function create()
    {
        return $this->fetch();
    }

    
    public function save(Request $request)
    {
        if ($request->isAjax()) {
            $data = $request->param();
            $result = $this->validate($data, 'Admin.save');
            if (true !== $result) {
                // 验证失败 输出错误信息
                return ['code' => 1, 'msg' => $result];
            } else {
                $adminModel = new AdminModel();
                $ret = $adminModel->save([
                   'username' => $data['username'],
                    'password' => md5($data['password']),
                    'uname' => $data['uname'],
                    
                    'tel' => $data['tel']

                ]);
                if (!!!$ret) {
                    return ['code' => 1, 'msg' => '添加失败!执行如下操作' . $adminModel->getLastSql()];
                } else {
                    return ['code' => 0, 'msg' => '添加成功!'];
                }

            }
        } else {
            $this->error('请求类型错误，请通过合法途径请求！');
        }
    }

    
    public function read(Request $request)
    {
        $data = $request->param();
        $adminModel = new AdminModel();
        $admin = $adminModel->find($data['id']);
        $this->assign('admin', $admin);
        return $this->fetch();
    }

    
    public function edit(Request $request)
    {
        $data = $request->param();
        $adminId = $data['id'];
        $adminModel = new AdminModel();
        $admin = $adminModel->find($adminId);
        $this->assign('admin', $admin);
        return $this->fetch();
    }

    
    public function update(Request $request)
    {
        if ($request->isAjax()) {
            $data = $request->param();
            $result = $this->validate($data, 'Admin.update');
			
            if (true !== $result) {
                return ['code' => 1, 'msg' => $result];
            } else {
				if (isset($data['password'])) {$pass = $data['password'];}else{$pass = "";}
                $adminModel = new AdminModel();
                $ret = $adminModel->allowField(true)->update([
                    'uname' => $data['uname'],
                   'password' => $pass,
                    'tel' => $data['tel']
                ], ['id' => $data['id']]);
                if (!!!$ret) {
                    return ['code' => 1, 'msg' => '修改失败！执行如下操作：' . $adminModel->getLastSql()];
                } else {
                    return ['code' => 0, 'msg' => '修改成功！'];
                }
            }
        } else {
            $this->error('请求类型错误，请通过合法途径请求！');
        }
    }


    
    public function edit_self(Request $request)
    {
        $admin_no = Session::get('adminname');
        $adminModel = new AdminModel();
        $admin = $adminModel->where(['admin_no' => $admin_no])->find();
        $this->assign('admin', $admin);

        

        return $this->fetch();
    }

    
    public function update_self(Request $request)
    {
        if ($request->isAjax()) {
            $data = $request->param();
            $result = $this->validate($data, 'Admin.update_self');
            if (true !== $result) {
                return ['code' => 1, 'msg' => $result];
            } else {
                $adminModel = new AdminModel();
                $ret = $adminModel->allowField(true)->update([
                    'uname' => $data['uname'],
                  
                    'tel' => $data['tel']
                  
                ], ['id' => $data['id']]);
                if (!!!$ret) {
                    return ['code' => 1, 'msg' => '修改失败！执行如下操作：' . $adminModel->getLastSql()];
                } else {
                    return ['code' => 0, 'msg' => '修改成功！'];
                }
            }
        } else {
            $this->error('请求类型错误，请通过合法途径请求！');
        }
    }


    
    public function delete(Request $request)
    {
        if ($request->isAjax()) {
            $data = $request->param();
            $adminModel = new AdminModel();
            $admin = $adminModel->get($data['id']);
            $ret = $admin->delete();
            if (!!!$ret) {
                return ['code' => 1, 'msg' => '删除失败!执行如下操作：' . $adminModel->getLastSql()];
            } else {
                return ['code' => 0, 'msg' => '删除成功!'];
            }
        } else {
            $this->error('请求类型错误，请通过合法途径请求！');
        }
    }
}
