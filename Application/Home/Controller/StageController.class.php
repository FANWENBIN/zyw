<?php
// 本类由系统自动生成，仅供测试用途
namespace Home\Controller;
use Think\Controller;
class StageController extends ComController {
    public function index(){
    	$actors = M('actors');
    	$where['newser'] = array('eq',1);
    	$where['status'] = array('in','1,2');
    	$list = $actors->where($where)->order('clickrate desc')->limit(0,18)->select();
    	$this->assign('list',$list);
        //规则
        $configure = M('configure');
        $rule = $configure->where('type = 1')->find();
        $this->assign('rule',$rule);
    	//Banner
    	$banner = M('banner');
    	$bannerval = $banner->where('type = 5')->select();
    	$this->assign('bannerval',$bannerval);
        //作品展示
        $stage = M('stageworks');
        $works = $stage->where('status = 1')->order('instime desc')->select();
        $this->assign('works',$works);
		$this->display();
    }
    //学员报名
   	public function apply(){
        
   	}
   
}