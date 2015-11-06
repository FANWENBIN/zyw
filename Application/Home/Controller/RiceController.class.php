<?php
namespace Home\Controller;
use Think\Controller;
//粉丝饭团类
class RiceController extends ComController {
    public function index(){
    	$this->assign('sign',6);
    	//粉丝社群
        $this->display();
    }


}