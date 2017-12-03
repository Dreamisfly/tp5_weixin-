<?php

namespace app\admin\controller;

use app\common\model\Category;
use think\Controller;
use think\Db;
use think\Request;

class CategoryController extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //
        $category = Category::where('status','1')->order('id asc')->paginate('3');
        $this->assign('category',$category);
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
        $data['name']=input('name');
        $data['path']=input('path');
        $data['status']=1;
        $ret = Category::create($data);
        if($ret){
            return $this->success('新分类创建成功','/admin/category/index','3');
        }else{
            return $this->error();
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
        $row = Category::get($id);
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
    public function update(Request $request)
    {
        //
        $id = input('id');
        $data['name']=input('name');
        $data['path']=input('path');
        $data['status']=1;

        $ret =Db::table('categorys')->where('id',$id)->update($data);
        if($ret){
            return $this->success('分类数据更新成功','/admin/category/index','2');
        }else{
            return $this->error('分类数据更新失败');
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
        $category = Category::get($id)->delete();
        if ($category){
            return $this->success('分类数据删除成功','/admin/category/index','3');
        }else{
            return $this->error('分类数据删除失败');
        }
    }
}
