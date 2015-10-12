<?php
// 
namespace Stage\Controller;
use Think\Controller;
class FuturestarController extends ComController {
	//明日之星首页显示
	public function index(){
		$committee  = M('committee');
		$commitval = $committee->where('status = 1')->select();
		$this->assign('commitval',$commitval);
		$this->assign('cur',8);
		$this->display();
		//echo $ip = get_client_ip();
	}
	

}