<?php
// 本类由系统自动生成，仅供测试用途
namespace Stage\Controller;
use Think\Controller;
class ComController extends Controller {

//
    public function vercklogin(){
    	//md5(xxzyw916);
    	$sign = session('uid');

    	if($sign != 'f8e4b89ebe09b7e060d30faf3f0b3047'){

    		  $this->success('请登陆',U('Index/index'),5);
    	}
        exit;
    }

    public function checkDump($data){
    	foreach($data as $key=>$val){
    		if(empty($val)){
    			return 0;
    		}
    	}
    	return 1;
    }
}
?>