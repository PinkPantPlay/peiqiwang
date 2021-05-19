<?php

namespace app\admin\model;

use think\Model;

class Bbs extends Model
{
    public function usera()
    {
        return $this->belongsTo('user','userid','id');
    }
}
?>