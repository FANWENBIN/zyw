<?php
// 
namespace Stage\Controller;
use Think\Controller;
class CommitteeController extends ComController {
	//演工委之声红黑榜首页显示
	public function index(){
		$committee  = M('committee');

		$commitval = $committee->where('status = 1')->select();
		$count      = $committee->where('status=1')->count();
		// 查询满足要求的总记录数
		$Page       = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
		$show       = $Page->show();// 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$commitval = $committee->where('status=1')->order('instime desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign('page',$show);// 赋值分页输出

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
	public function delete(){
		$id = I('get.id');
		$committee = M('committee');
		$data['status'] = 0;
		$sign = $committee->where('id='.$id)->save($data);
		if($sign){
			$this->success('删除成功',U('index'));
		}else{
			$this->error('未删除');
		}

	}

	

}