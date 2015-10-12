<?php
// 
namespace Stage\Controller;
use Think\Controller;
class CommitteeController extends ComController {
	//首页显示
	public function index(){
		$committee  = M('committee');
		$commitvval = $committee->where('status = 1')->select();
		$this->assign('commitval',$commitval);
		$this->assign('cur',7);
		$this->display();
		//echo $ip = get_client_ip();
	}
	public function add(){
		
	}

}