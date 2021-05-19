<?php 
namespace app\admin\controller;

use \think\Controller;
use \think\Session;

class Base extends Controller
{
	//放置一些基础函数，其它类可以继承这些函数，调用这些函数
	public function getDeleInfo($table,$where=array(),$id =array()){//删除
		$model = model($table);
		$result = $model->where($where)->delete($id);
	    if($result){
	    	return true;
	    }else{
	    	return false;
	    }
	}
	public function getEditInfo($table,$where=array(),$id =array()){//修改
		$model = model($table);
		$ini['id'] = array('in',$id);
		$result = $model->where($ini)->setField('status',-1);
	    if($result){
	    	return true;
	    }else{
	    	return false;
	    }
	}
}