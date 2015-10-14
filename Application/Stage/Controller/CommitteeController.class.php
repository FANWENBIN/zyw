<?php
// 
namespace Stage\Controller;
use Think\Controller;
class CommitteeController extends ComController {
	//演工委之声首页显示
	public function index(){
		$committee  = M('committee');
		$commitval = $committee->where('status = 1')->select();
		$this->assign('commitval',$commitval);
		$this->assign('cur',7);
		$this->display();
		//echo $ip = get_client_ip();
	}
	//新增演工委
	public function add(){
		$submit = I('post.submit');
		if(empty($submit)){
			$this->assign('cur',7);
			$this->display();
		}else{
			$data['title']   = I('post.title');
			$data['type']    = I('post.type');
			$data['img']     = I('post.img');
			$data['digest']  = I('post.digest');
			$data['content'] = I('post.content');
			$sign = $this->checkDump($data);
			$sign || $this->error('数据不可为空');
			$data['top']     = I('post.top');
			if($data['top'] == 1){
				$data['topimg'] = I('post.topimg');
			}
			$data['instime'] = time();
			$committee = M('committee');
			$sign = $committee->add($data);
			if($sign){
				$this->success('新增成功',U('Committee/index'));
			}else{
				$this->error('新增失败');
			}
		}
		
	}
	//演工委修改
	public function info(){
		$id = I('get.id');
		$submit = I('post.submit');
		$committee = M('committee');
		if(empty($submit)){
			$commitval = $committee->where('id='.$id)->find();
			$this->assign('list',$commitval);
			$this->assign('cur',7);
			$this->display();
		}else{
			$data['title']   = I('post.title');
			$data['type']    = I('post.type');
			$data['img']     = I('post.img');
			$data['digest']  = I('post.digest');
			$data['content'] = I('post.content');
			$sign = $this->checkDump($data);
			$sign || $this->error('数据不可为空');
			$data['top']     = I('post.top');
			if($data['top'] == 1){
				$data['topimg'] = I('post.topimg');
			}else{
				$data['topimg'] = null;
			}
			$id = I('post.id');
			$committee = M('committee');
			$sign = $committee->where('id='.$id)->save($data);
			if($sign){
				$this->success('修改成功',U('Committee/index'));
			}else{
				$this->error('未做新增');
			}

		}
	}

}