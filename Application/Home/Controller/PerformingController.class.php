<?php
namespace Home\Controller;
use Think\Controller;
//演艺库类
class PerformingController extends ComController {
    public function index(){
        $this->display();
    }
    //演艺人全部按照字母排序接口数据
    public function allperforming(){
    	$type = trim(I('get.type'));
    	switch ($type) {
    		case '1':       //男演员
    			$where['sex'] = 1;
    			break;
    		case '2':		//女演员
    			$where['sex'] = 2;
    			break;
    		case '3':		//港台演员
    			$where['area'] = 1;
    			break;
    		case '4':		//内地演员
    			$where['area'] = 2;
    			break;
    		case '5':		//演艺新人
    			$where['newser'] = 1;
    			break;
    		default:

    			break;
    	}
    	$actors = M('actors');
    	$data = [];

    	foreach(range('A','Z') as $v){
			$data[$v] = $actors
						->field('id,headimg,name')
						->where("status <> 0 and initial ='".$v."'")
						->where($where)
						->select();
			foreach($data[$v] as $key=>$val){
				$data[$v][$key]['headimg'] = './Uploads'.$val['headimg'];
			}
			if($data[$v] === false){
				ajaxReturn(101,'系统错误','');
			}
		}
		ajaxReturn(0,'',$data);
    }


}
