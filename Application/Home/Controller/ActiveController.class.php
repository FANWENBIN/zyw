<?php
// 本类由系统自动生成，仅供测试用途
namespace Home\Controller;
use Think\Controller;
class ActiveController extends ComController {
	//首页显示
    public function index(){

		$this->display();
		//echo $ip = get_client_ip();
    }
    //========================前台调用活动查询接口==============Start//
    /*类型分类查询*/
    public function activetype(){
    	$type = I('get.type'); //全部 0，线上 1 ，线下 2， 
    	$time = I('get.time'); //全部 0，今天 1 ， 明天 2，后天 3，周末 4，本周 5 ，本周后 6，未开始 7，已结束 8 。
    	$active = M('active');
    	switch ($type) {
    		case '1':
    			$data['linetype'] = 1;
    			break;
    		case '2':
    			$data['linetype'] = 0;
    			break;
    		case '0':
    			break;
    		default:
   				ajaxReturn('101','参数错误','');
    			break;
    	}
    	switch ($time) {
    		case '1':
    			$data['begin_time'] = array('lt',time());
    			$data['last_time']  = array('gt',time());
    			break;
    		case '2':
    			$data['begin_time'] = array('lt',time()+86400);
    			$data['last_time']  = array('gt',time()+86400);
    			break;
    		case '3':
    			$data['begin_time'] = array('lt',time()+172800);
    			$data['last_time']  = array('gt',time()+172800);
    			break;
    		case '4':
    			$data['week'] = 1;
    			break;
    		case '5':
    			$data['begin_time'] = array('lt',get_week_time('last'));
    			$data['last_time']  = array('gt',get_week_time());
    			$data['_logic'] = 'OR';
    			break;
    		case '6':
    			$data['begin_time'] = array('gt',get_week_time('last'));
    			break;
    		case '7':
    			$data['begin_time'] = array('gt',time());
    			break;
    		case '8':
    			$data['last_time'] = array('lt',time());
    			break;
    		case '0':
    			break;
    		default:
   				ajaxReturn('101','参数错误','');
    			break;
    	}
    	$activeval = $active->where($data)->select();
    	if($val === false){
    		ajaxReturn('102','查询数据有误','');
    	}else{
    		foreach($activeval as $key=>$val){
    			$activeval[$key]['begin_time'] = date('m-d',$val['begin_time']);
    			$activeval[$key]['last_time'] = date('m-d',$val['last_time']);
    		}
    		ajaxReturn('0','',$activeval);
    	}
    }

}