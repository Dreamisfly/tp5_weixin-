<?php

namespace app\common\model;

use think\Model;
use traits\model\SoftDelete;

class Menu extends Model
{
    //
    protected $table = 'menu';
    protected $autoWriteTimestamp = 'datetime';//开启并且使用timestamp类型
    protected $createTime = 'create_time';    //插入记录时自动维护的字段
    protected $updateTime = 'update_time';    //更新记录时自动维护的字段  三个mp一起写
    //指明软删除字段
    protected $deleteTime = 'delete_time';
    use SoftDelete;
}
