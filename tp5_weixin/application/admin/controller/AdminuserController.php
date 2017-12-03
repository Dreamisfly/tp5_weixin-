<?php

namespace app\admin\controller;

use app\common\model\AdminUser;
use think\Controller;
use think\Db;
use think\Request;

class AdminuserController extends Controller
{
    public function index()
    {

    }
    public function login()
    {
        //登陆
        $this->view->engine->layout(false);
        return $this->fetch();
    }
    public function dologin()
    {
        //登陆检测
        if(!captcha_check(input('captcha'),'login')){
            return $this->error('验证码输入错误，请重试');
        }
        $condition = [];
        // 获取表单数据
        $condition['adminusername'] = input('username');
        $condition['adminpassword'] = input('password');
        // 获取匹配记录
        $user = AdminUser::where($condition)->find();
        // 判断
        if ($user) {    // 登录成功
            // 写入session
            session('loginedAdminUser', $user->nickname);
            // 跳转
            return $this->success('用户登录成功！', '/admin/index/index');
        } else {
            return $this->error('用户名或密码错误！');
        }
    }
    public function register()
    {
        //注册
        $this->view->engine->layout(false);
        return $this->fetch();
    }
    public function doregister(Request $request)
    {
        //注册检测
        if(!captcha_check(input('captcha'),'register')){
            return $this->error('验证码输入错误，请重试');
        }
        $user = new AdminUser();
        // 获取表单数据
        $user->name = $request->param('adminusername');
        $user->pswd = input('adminpassword');
        // 插入到数据库中
        if ($user->save()) { // 注册成功
            return $this->success('注册成功！', '/admin/adminuser/login');
        } else {    // 注册失败
            return $this->error('注册失败，请重试！');
        }
    }
    public function logout()
    {
        //注销
        session('loginedAdminUser', null);
        return $this->redirect('/');
    }
    public function shoplist()
    {
        //获取购买列表
    }

}