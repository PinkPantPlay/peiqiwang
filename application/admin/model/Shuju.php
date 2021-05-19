<?php
namespace app\admin\model;
use think\Model;

class Shuju extends Model
{
    public function category()
    {
        return $this->belongsTo('category','categoryid','id');
    }
	public function category2()
    {
        return $this->belongsTo('category','categoryid2','id');
    }
	public function category3()
    {
        return $this->belongsTo('category','categoryid3','id');
    }
	public function user1()
    {
        return $this->belongsTo('user','userid','id');
    }
}
