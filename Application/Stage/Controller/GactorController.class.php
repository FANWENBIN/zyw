<?php
namespace Stage\Controller;
use Think\Controller;
//好演员类
class GactorController extends ComController {
    //首页显示
    public function index(){
        $this->vercklogin();
        $this->display('gactor');
        //echo md5('xxxzyw916');        
    }

    

}