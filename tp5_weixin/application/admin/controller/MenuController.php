<?php

namespace app\admin\controller;


use app\common\model\Menu;
use think\Controller;
use think\Request;
use weixin\wxMenu;

class MenuController extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //
        $menu = new Menu();
        $id= $menu::withTrashed()->count();
        $menu = Menu::where('delete_time',"0000-00-00 00:00:00")->order("Id desc")->where('Id','>=',$id)->select();
        $menus = Menu::where('delete_time',"0000-00-00 00:00:00")->order('Id asc')->paginate(2);
// 把分页数据赋值给模板变量list
        $this->assign('menu',$menu);
        $this->assign('menus', $menus);
// 渲染模板输出
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
        $menu = new \weixin\wxMenu();
        $data =[];
        $data['type'] = input('type');
        $data['name'] = input('name');
        $data['key'] = input('key');
        $data['url'] = input('url');
        $result = Menu::create($data);
        if($result){
            $menu_data = [
                'button' =>[
                    [
                        'type' =>$data['type'],
                        'name' =>$data['name'],
                        'key' =>$data['key'],
                        'url' => $data['url'],
                    ],
                ],
            ];
            $ret = $menu->createMenu($menu_data);
            $ret = json_decode($ret,true);
            if(!$ret['errcode']){
                return $this->success('菜单创建成功','/admin/menu/create','3');
            }
        }else{
            return $this->error('菜单写入数据库失败');
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
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //// 获取id
        $menu = Menu::get($id);
        if ($menu->delete()) {
            return "ok";
        } else {
            return "error";
        }
    }
    public function sdelete($id)
    {
        $menu = Menu::get($id);
        if ($menu->delete()){
            $menu = new \weixin\wxMenu();
            $ret = $menu->deleteMenu();
            $ret = json_decode($ret,true);
            if(!$ret['errcode']){
                return "ok";
            }else{
                return $this->error('微信端菜单删除失败,请重新尝试');
            }
        }else{
            return "error";
        }
    }
}
