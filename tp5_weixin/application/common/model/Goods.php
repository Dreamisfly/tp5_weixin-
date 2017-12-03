<?php

namespace app\common\model;

use think\Model;

class Goods extends Model
{
    //
    protected $table = 'goodss';
    public function category()
    {
        return $this->belongsTo('Category','cid');
    }
    public function users()
    {
        return $this->belongsToMany('User','user_goods','uid','gid');
    }
}
