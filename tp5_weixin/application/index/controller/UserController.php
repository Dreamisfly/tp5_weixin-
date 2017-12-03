<?php

namespace app\index\controller;
use app\common\model\Category;
use app\common\model\User;
use think\captcha\Captcha;
use think\Controller;
use think\Db;
use think\Request;

class UserController extends Controller
{
    public function index()
    {


        $this->view->engine->layout(false);
        //查看用户自己的信息，用户个人信息列表

        $username = session('loginedUser');

        $user = Db::table('users')->where('name',$username)->select();
        $this->assign('user',$user[0]);
        return $this->fetch();
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
        $condition['name'] = input('username');
        $condition['pswd'] = md5(input('password'));
        // 获取匹配记录
        $user = User::where($condition)->find();
        // 判断
        if ($user) {    // 登录成功
            // 写入session
            session('loginedUser', $user->name);
            // 跳转
            return $this->success('用户登录成功！', '/');
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
        $user = new User();
        // 获取表单数据
        $user->name = $request->param('username');
        $user->pswd = md5(input('password'));
        // 插入到数据库中
        if ($user->save()) { // 注册成功
            return $this->success('注册成功！', '/index/user/login');
        } else {    // 注册失败
            return $this->error('注册失败，请重试！');
        }
    }
    public function logout()
    {
        //注销
        session('loginedUser', null);
        return $this->redirect('/');
    }
    public function shoplist()
    {
        //获取购买列表
    }

}
