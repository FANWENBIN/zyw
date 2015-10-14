<?php
namespace Stage\Controller;
use Think\Controller;
//视频类
class VedioController extends ComController {
    //首页显示
    public function index(){
    	$vedio = M('vedio');
    	$vedioval = $vedio->where('status = 1')->select();

    	$this->assign('list',$vedioval);
    	$this->assign('cur',9);
		$this->display();
    }
    //修改视频
    public function edit(){
    	$submit = I('post.submit');
    	$vedio  = M('vedio');
    	if(empty($submit)){
    		$id = I('get.id');
    		$this->assign('cur',9);
    		$vedioval = $vedio->where('id='.$id)->find();
    		$this->assign('vedioval',$vedioval);
    		$this->display();
    	}
    }
    //删除视频
    public function delete(){
    	$id = I('get.id');
    	$vedio = M('vedio');
    	$data['status'] = 1;

    	$sign = $vedio->where('id='.$id)->save($data);
    	if($sign){
    		$this->success('删除成功',U('Vedio/index'));
    	}else{
    		$this->error('未删除数据');
    	}


    }
}