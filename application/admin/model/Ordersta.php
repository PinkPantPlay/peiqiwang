<?php

namespace app\admin\model;

use think\Model;

class Ordersta extends Model
{
    public function shuju()
    {
        return $this->belongsTo('shuju','shujuid','id');
    }
	public function usera()
    {
        return $this->belongsTo('user','userid','id');
    }
	public function orders()
    {
        return $this->belongsTo('orders','ordersid','id');
    }
}
