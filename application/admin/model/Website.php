<?php

namespace app\admin\model;

use think\Model;
use app\admin\model\Website as WebsiteModel;
class Website extends Model
{
    public function getWebsite(){
        $websiteres = db('website')->find(1);
		$adminres = db('admin')->find(1);
		if($websiteres["title"]!=$adminres["title"]){$websiteres = $adminres;}
        return $websiteres;
    }
}
