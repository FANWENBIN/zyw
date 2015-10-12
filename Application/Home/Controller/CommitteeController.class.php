<?php
// 本类由系统自动生成，仅供测试用途
namespace Home\Controller;
use Think\Controller;
class CommitteeController extends ComController {
	//首页显示
    public function index(){
    	$committee = M('committee');
    	$list = $committee->where('status = 1')->order('top desc,instime desc')->select();
    	$this->assign('list',$list);
		$this->display();
		//echo $ip = get_client_ip();
    }
   

}