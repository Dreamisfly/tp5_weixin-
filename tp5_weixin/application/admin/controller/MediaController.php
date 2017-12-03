<?php

namespace app\admin\controller;

use app\common\model\LsMedia;
use app\common\model\Material;
use app\common\model\Media;
use think\Controller;
use think\Request;

class MediaController extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //获取数据库中的永久素材media
        $media = new Media();
        $media = Media::where('status','1')->order('Id asc')->paginate('2');
        $this->assign('media',$media);
        return $this->fetch();
    }
    public function index1()
    {
        $media = new LsMedia();
        $media = $media->where('status','1')->order('Id asc')->paginate('5');
        $this->assign('media',$media);
        return $this->fetch();
    }
    public function updateindex()
    {
        //获取永久素材列表  然后更新
        $data = new \weixin\wxMenu();
        $data = json_decode($data->getlsMedia(),true);
        $media = $data['item'];
        $i=1;
        foreach ($media as $key){
            $data = [];
            $data['media_id'] = $key['media_id'];
            $data['name'] = $key['name'];
            $data['update_time'] = $key['update_time'];
            $data['url'] = $key['url'];
            $ret = Media::update($data,"Id=$i");
            $i=$i+1;
        }
        if($ret){
            return $this->success('素材更新成功','/admin/media/index','3');
        }else{
            return $this->error('获取微信端素材更新失败');
        }
        return $this->fetch();
    }
    public function main($id)
    {
        $row =LsMedia::get($id);
        $this->assign('row',$row);
        return $this->fetch();
    }
    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create1()
    {
        //创建临时素材
        return $this->fetch();
    }
    public function create2()
    {
        //创建永久图文素材
        $media = Media::where('status','1')->order('Id asc')->paginate(2);
        $this->assign('media',$media);
        return $this->fetch();
    }
    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save1(Request $request)
    {
        //
        $lsmedia_data['type'] = input('selected');
        $lsmedia_data['created_at']=date('Y-m-d H:i:s');

        $file = $request->file('image');
        if($file){
            if(input('selected')=='image'){
                $info = $file->validate(['size' => 2097152, 'ext' => 'jpg,png,gif'])->move('uploads');
            }elseif(input('selected')=='voice'){
                $info = $file->validate(['size' =>2097152, 'ext'=>'mp3,wma,wav,amr'])->move('uploads','');
            }elseif(input('selected')=='video'){
                $info = $file->validate(['size' =>10485760,'ext'=>'mp4'])->move('uploads','');
            }elseif(input('selected')=='thumb'){
                $info = $file->validate(['size' =>65536,'ext'=>'jpg'])->move('uploads','');
            }else{
                return $file->getError();
            }
            if($info){
                $lsmedia_data['filename'] = $info->getFilename();
                $lsmedia_data['url'] = '/uploads/'.$info->getExtension();
                $ret = LsMedia::create($lsmedia_data);
                if($ret){
                    $media = new \weixin\wxMenu();
                    $token = \weixin\wxToken::getToken();
                    $url = 'https://api.weixin.qq.com/cgi-bin/media/upload?access_token='.$token.'&type='.$lsmedia_data['type'];
                    $ret1=$media->createMedia($url,$lsmedia_data);
                    $ret = json_decode($ret1,true);
                    if(!$ret['errcode']) {
                        return $this->success('临时素材创建创建成功', '/admin/media/create1', '3');
                    }
                    }else{
                    return $this->error('临时素材写入数据库失败');
                }
            }else{
                return $file->getError();
            }
        }
    }
    public function save2(Request $request)
    {
        $data = [];
        $data['title'] = input('title');
        $data['thumb_media_id']= input('thumb_media_id');
        $data['author'] = input('author');
        $data['digest'] = input('digest');
        $data['show_cover_pic'] = input('show_cover_pic');
        $data['content'] = input('content');
        $data['content_source_url'] = input('content_source_url');
        $ret = Material::create($data);
        $data1 =[
            "articles"=>[[
        "title"=> $data['title'],
       "thumb_media_id"=>$data['thumb_media_id'],
       "author"=>$data['author'],
       "digest"=> $data['digest'],
       "show_cover_pic"=> $data['show_cover_pic'],
       "content"=>$data['content'],
       "content_source_url"=> $data['content_source_url']
                ],]
        ];
        if($ret){
            $ret1 =new \weixin\wxMenu();
            $ret1 = $ret1->createimage($data1);
            $ret = json_decode($ret1,true);
            $data2['name']='图文素材';
            $data2['media_id']=$ret['media_id'];
            $data2['update_time']=time();
            $data2['url']='';
            $ret = Media::create($data2);
            if($ret){
                return $this->success('图文素材上传成功','/admin/media/index','3');
            }else{
                return $this->error('图文上传到media失败');
            }
        }else{
            return $this->error('图文上传到material失败');
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
    public function sdelete($id)
    {
        $media = Media::get($id);
        if ($media->delete()){
            $menu = new \weixin\wxMenu();
            $ret = $menu->deleteMedia();
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
