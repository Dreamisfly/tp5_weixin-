<?php

namespace app\common\model;

use think\Model;
use traits\model\SoftDelete;

class User extends Model
{
    //

    protected $table = 'users';
    //是否开启自动开启时间戳
    protected $autoWriteTimestamp = 'datetime';//开启并且使用timestamp类型
    protected $createTime = 'create_time';    //插入记录时自动维护的字段
    protected $updateTime = 'update_time';    //更新记录时自动维护的字段  三个mp一起写
    //指明软删除字段
    protected $deleteTime = 'delete_time';
    protected $_validate = array(
        array('username', 'require', '用户名不能为空！'), //默认情况下用正则进行验证
        array('username', '', '该用户名已被注册！', 0, 'unique', 1), // 在新增的时候验证name字段是否唯一
        array('password', '/^([a-zA-Z0-9@*#]{6,22})$/', '密码格式不正确,请重新输入，密码最少6位！', 0),
    );
    protected $_auto = array (
        array('password', 'md5', 3, 'function') , // 对password字段在新增和编辑的时候使md5函数处理
    );

    //引入软删除 trait
    use SoftDelete;
    protected function is_agree()
    {
        // 获取POST数据
        $agree = input('post.agree', 0, 'intval');
        // 验证
        if ($agree) {
            return true;
        } else {
            return false;
        }
    }
    public function userinfo()
    {
        //参数1   关联类的类名称
        //参数2   外键名称
        return $this->hasOne('Userinfo','uid');

    }
    public function goodss()
    {
        //用户可以购买多个商品
        //多对多关系  1.关联表的模型类名  2.中间表的数据表名称 3.在中间表中关联表的外键  4.在中间表中当前表的外键
        return $this->belongsToMany('Goods','user_goods','gid','uid');
    }
}
