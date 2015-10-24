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