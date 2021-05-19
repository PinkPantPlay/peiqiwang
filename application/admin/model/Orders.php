<?php

namespace app\admin\model;

use think\Model;

class Orders extends Model
{
    public function shuju()
    {
        return $this->belongsTo('shuju','shujuid','id');
    }
	public function usera()
    {
        return $this->belongsTo('user','userid','id');
    }
}
