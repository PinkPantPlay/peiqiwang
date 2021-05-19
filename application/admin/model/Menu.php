<?php

namespace app\admin\model;
use think\Session;
use think\Model;
use app\admin\model\Menu as MenuModel;
class Menu extends Model
{
    public function getNavMenus(){
        $auth = Session::get('auth');
		$menures=db('menu')->where(array('pid'=>0))->where(array('type'=>0))->where(array('isno'=>0))->where(array('auth'=>$auth))->order('grade asc')->select();
        foreach ($menures as $k => $v) {
            $children=db('menu')->where(array('pid'=>$v['id']))->where(array('isno'=>0))->where(array('type'=>0))->order('grade asc')->select();
            if($children){
                $menures[$k]['children']=$children;
            }else{
                $menures[$k]['children']=0;
            }
        }
		return $menures;
    }
}
