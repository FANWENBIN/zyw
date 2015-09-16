<?php
// 
namespace Home\Controller;
use Think\Controller;
class NewsController extends ComController {
    public function index(){
		$this->display('news');
    }
}