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
        $condition = I('get.condition');
        if(!empty($condition)){
            $where['name|achievement'] = array('like','%'.$condition.'%'); 
        }   $where['status'] = array(array('eq',1),array('eq',2),'or');
    	foreach(range('A','Z') as $v){
			$data[$v] = $actors
						->field('id,headimg,name')
						->where("initial ='".$v."'")
						->where($where)
						->select();
			foreach($data[$v] as $key=>$val){
				$data[$v][$key]['headimg'] = './Uploads'.$val['headimg'];
			}
			if($data[$v] === false){
				ajaxReturn(101,'请求失败','');
			}
		}
		ajaxReturn(0,'',$data);
    }

    //搜索演员
    public function actorssearch(){

        $condition = I('get.condition');
        $data['name|achievement'] = array('like','%'.$condition.'%');
        $data['status'] = array(array('eq',1),array('eq',2),'or');
        $actors = M('actors');
        $actorsval = $actors->where($data)->select();
        if($actorsval === false){
            ajaxReturn(101,'请求失败','');
        }else{
            ajaxReturn(0,'',$actorsval);
        }
    }


}
