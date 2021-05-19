<?php


namespace app\admin\model;



use think\Model;

class Ad extends Model
{
    public function ad()
    {
        return $this->belongsTo('ad','id','sid');
    }
    public function shuju()
    {
        return $this->belongsTo('shuju','sid','id');
    }
}