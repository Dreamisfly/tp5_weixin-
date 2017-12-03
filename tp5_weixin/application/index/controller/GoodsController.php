<?php

namespace app\index\controller;

use app\common\model\Category;
use think\Controller;
use think\Request;

class GoodsController extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function shouji()
    {
        $c = Category::get(1);
        //由分类获取商品
        $gs = $c->goodss()->where('price>1000')->select();
        $gs = $gs->toArray();
        $category = Category::all();
        $this->assign('category',$category);
        $this->assign('gs',$gs);
        return $this->fetch();
    }
    public function bijibendiannao()
    {
        $c = Category::get(2);
        //由分类获取商品

        $gs = $c->goodss()->where('price>1000')->select();
        $gs = $gs->toArray();
        $category = Category::all();
        $this->assign('category',$category);
        $this->assign('gs',$gs);
        return $this->fetch();
    }

}
