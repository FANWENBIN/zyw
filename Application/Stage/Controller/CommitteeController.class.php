<?php
// 
namespace Stage\Controller;
use Think\Controller;
class CommitteeController extends ComController {
	//首页显示
    public function index(){
    	$this->assign('cur',7);
		$this->display();
		//echo $ip = get_client_ip();
    }
   

}