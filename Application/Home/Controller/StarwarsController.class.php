<?php
namespace Home\Controller;
use Think\Controller;
//首页类
class StarwarsController extends ComController {
    public function index(){
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
    	$this->display();
    }
}