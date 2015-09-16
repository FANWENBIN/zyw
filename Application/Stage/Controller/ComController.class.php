<?php
// 本类由系统自动生成，仅供测试用途
namespace Stage\Controller;
use Think\Controller;
class ComController extends Controller {

//
    public function vercklogin(){
    	//md5(xxzyw916);
    	$sign = session('uid');
    	
    	if($sign != md5('xxxzyw916')){
    		return 0;
    	}
    }
}