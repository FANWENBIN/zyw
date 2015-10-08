<?php
namespace Stage\Controller;
use Think\Controller;
//活动类
class ActiveController extends ComController {
    //首页显示
    public function index(){
		$this->vercklogin();

        //$this->display();
    }
	
}