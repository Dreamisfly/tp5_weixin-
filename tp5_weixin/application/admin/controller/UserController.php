<?php

namespace app\admin\controller;

use app\common\model\User;
use think\Controller;
use think\Request;

class UserController extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //
        $user = User::withTrashed()->where('delete_time','0000-00-00 00:00:00')->order('id asc')->paginate('3');
        $this->assign('user',$user);
        return $this->fetch();
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
        return $this->fetch();
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //
        $data['name']=input('name');
        $data['pswd']=input('pswd');
        $data['status']=1;
        $ret = User::create($data);
        if($ret){
            return $this->success('新用户创建成功','/admin/user/index','3');
        }else{
            return $this->error('用户创建失败');
        }
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        //
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //
        $row = User::withTrashed()->where('id',$id)->find();
        $this->assign('row',$row);
        return $this->fetch();
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        //
        $id = input('id');
        $data['name']=input('name');
        $data['pswd']=input('pswd');
        $data['status']=1;

        $ret =User::withTrashed()->where('id',$id)->update($data);
        if($ret){
            return $this->success('用户信息更新成功','/admin/user/index','2');
        }else{
            return $this->error('用户信息更新失败');
        }
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //
        $ret = User::withTrashed()->where('id',$id)->delete();
        if ($ret){
            return $this->success('用户信息删除成功','/admin/admin/index','3');
        }else{
            return $this->error('用户信息删除失败');
        }
    }
}
