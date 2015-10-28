<?php
namespace Home\Controller;
use Think\Controller;
//演艺库类
class PerformingController extends ComController {
    public function index(){
        $this->assign('sign',11);
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
    //明星详情
    public function actorinfo(){
        $id = I('get.id');
        //明星信息
        $actors = M('actors');
        $actorsval = $actors->where('id='.$id)->find();
        session('actors',$actorsval);
        //点击率
        $data['clickrate'] = $actorsval['clickrate']+1;
        $actors->where('id='.$id)->save($data);
        $this->assign('actorsval',$actorsval);
        //明星代表作
        $actors_production = M('actors_production');
        $production = $actors_production->where('actorsid='.$id)->select();
        $this->assign('production',$production);
        //明星动态
        $news = M('news');
        $where['status'] = 1;
        $where['keywords'] = array('like', $actorsval['name']);
        $newsval = $news->where($where)->select();
        $this->assign('newsval',$newsval);
        //推荐活动
         $active = M('active');
         $activeval = $active->where('status = 1')->order('`order` desc,instime desc,concern desc')->find();
         $this->assign('activeval',$activeval);
        $this->assign('sign',11);
        $this->display();
    }
    //提交演员资料待审核
    public function newacter(){
$this->assign('sign',11);
        $this->display();

    }

}
