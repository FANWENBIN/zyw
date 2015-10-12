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

			$this->display();
		}else{

		}
		
	}
	//演工委修改
	public function info(){
		$id = I('get.id');
		$submit = I('post.submit');
		if(empty($submit)){
			$this->display();
		}else{

		}
	}

}