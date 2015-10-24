<?php
namespace Home\Controller;
use Think\Controller;
//首页类
class StarwarsController extends ComController {
    public function index(){
       //Banner
        $banner = M('banner');
        $bannerval = $banner->where('type = 6')->select();
        $this->assign('bannerval',$bannerval);

        
        $recommend = M('recommend');
        //当代艺术家
        $artists   = $recommend->where('type=1')->select();
        $this->assign('artists',$artists);
        //导演
        $director  = $recommend->where('type=2')->select();
        $this->assign('director',$director);
        //制作人
        $producer  = $recommend->where('type=3')->select();
        $this->assign('producer',$producer);
        //编剧
        $scriptwriter = $recommend->where('type=4')->select();
        $this->assign('scriptwriter',$scriptwriter);

        
        $this->display();
    }
    public function starinfo(){
    	$id = I('get.id');
    	$recommend = M('recommend');
    	$recommendval = $recommend->where('id='.$id)->find();
    	$this->assign('list',$recommendval);
    	$recommend_production = M('recommend_production');
    	$production = $recommend_production->where('recommendid='.$id)->select();
    	$this->assign('production',$production);   //代表作
    	$this->assign('default','<li>暂未上传代表作</li>');
    	$this->display();
    }
}