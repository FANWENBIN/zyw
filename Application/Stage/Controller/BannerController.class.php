<?php
namespace Stage\Controller;
use Think\Controller;
//
class BannerController extends ComController {
    //banner设置
    public function index(){
        $this->vercklogin();
        $this->display();    
    }
}