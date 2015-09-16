<?php
namespace Stage\Controller;
use Think\Controller;
//首页类
class IndexController extends ComController {
    public function index(){
		$this->display('login');
    }
}