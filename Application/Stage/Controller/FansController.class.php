<?php
namespace Stage\Controller;
use Think\Controller;
//视频类
class VedioController extends ComController {
    //首页显示
    public function index(){
    	$vedio = M('vedio');
    	$vedioval = $vedio->where('status = 1')->order('instime desc')->select();

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
    	}else{
    		$id = I('get.id');
    		$data['title'] = I('post.title');
    		$data['type']  = I('post.type');
    		$data['href']  = I('post.href');
    		$data['img']   = I('post.img');
            $data['bigimg'] = I('post.bigimg');
    		$sign = $this->checkDump($data);
    		if(!$sign){
    			$this->error('主要信息不可为空');
    		}
			$data['recommend'] = I('post.recommend');
			if($data['recommend'] == 1){
				$data['topimg'] = I('post.topimg');
			}
    		$sign = $vedio->where('id='.$id)->save($data);
    		if($sign){
    			$this->success('修改成功',U('Vedio/index'));
    		}else{
    			$this->error('未做任何修改');
    		}
    	}
    }
    //删除视频
    public function delete(){
    	$id = I('post.id','','intval');
    	$vedio = M('vedio');
    	$data['status'] = 0;
    	$sign = $vedio->where('id='.$id)->save($data);
    	if($sign){
    		$this->success('删除成功',U('Vedio/index'));
    	}else{
    		$this->error('未删除');
    	}
    }
    //新增数据
    public function add(){
    	$submit = I('post.submit');
    	$vedio  = M('vedio');
    	if(empty($submit)){
    		$this->assign('cur',9);
    		$this->display();
    	}else{
    		$data['title'] = I('post.title');
    		$data['type']  = I('post.type');
    		$data['href']  = I('post.href');
    		$data['img']   = I('post.img');
            $data['bigimg'] = I('post.bigimg');
    		$sign = $this->checkDump($data);
    		if(!$sign){
    			$this->error('主要信息不可为空');
    		}
            $data['status'] = 4;
			$data['recommend'] = I('post.recommend');
			if($data['recommend'] == 1){
				$data['topimg'] = I('post.topimg');
			}
			$data['instime'] = time();

    		$sign = $vedio->add($data);
    		if($sign){
    			$this->success('新增成功',U('Vedio/index'));
    		}else{
    			$this->error('未做任何新增');
    		}
    	}

    }
}