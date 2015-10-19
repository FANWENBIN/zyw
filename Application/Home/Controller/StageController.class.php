<?php
// 本类由系统自动生成，仅供测试用途
namespace Home\Controller;
use Think\Controller;
class StageController extends ComController {
    public function index(){
    	$actors = M('actors');
    	$list = $actors->where('newser = 1')->order('clickrate desc')->limit(0,18)->select();
    	$this->assign('list',$list);
		$this->display();
    }
    //学员报名
   	public function apply(){

   	}
   
}