<?php
namespace Home\Controller;
use Think\Controller;
//首页类
class IndexController extends ComController {
    public function index(){
    	/*echo __ROOT__.'： 会替换成当前网站的地址（不含域名） ';
    	echo __APP__.'： 会替换成当前应用的URL地址 （不含域名）';
    	echo __MODULE__.'：会替换成当前模块的URL地址 （不含域名）';
    	echo __CONTROLLER__.'（__或者__URL__ 兼容考虑）： 会替换成当前控制器的URL地址（不含域名）';
    	echo __ACTION__.'：会替换成当前操作的URL地址 （不含域名）';
    	echo __SELF__.'： 会替换成当前的页面URL';
    	echo __PUBLIC__ ;die();*/

		$this->display();
    }
}