<?php
namespace Home\Controller;
use Think\Controller;
//首页类
class StarwarsController extends ComController {
    public function index(){
        $this->display();
    }
}