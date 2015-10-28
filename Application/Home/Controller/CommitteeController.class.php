<?php
// 本类由系统自动生成，仅供测试用途
namespace Home\Controller;
use Think\Controller;
class CommitteeController extends ComController {
	//首页显示
    public function index(){
    	$committee = M('committee');
    	//推荐置顶之声
    	$list = $committee
    			->where('status = 1 and top = 1')
    			->order('top desc,instime desc')
    			->select();
    	$this->assign('list',$list);
    	//形象监督
    		//红榜
    	$count = $committee->where('status=1 and type=1')->count();// 查询满足要求的总记录数
    	$Page  = new \Think\Page($count,9);// 实例化分页类 传入总记录数和每页显示的记录数(25)
    	$show  = $Page->show();// 分页显示输出
    	// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
    	$list = $committee->where('status=1 and type=1')->order('instime')->limit($Page->firstRow.','.$Page->listRows)->select();
    	$this->assign('redcom',$list);// 赋值数据集
    	$this->assign('redpage',$show);// 赋值分页输出

    		//黑榜
    	$count = $committee->where('status=1 and type=2')->count();// 查询满足要求的总记录数
    	$Page  = new \Think\Page($count,9);// 实例化分页类 传入总记录数和每页显示的记录数(25)
    	$show  = $Page->show();// 分页显示输出
    	// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
    	$list = $committee->where('status=1 and type=2')->order('instime')->limit($Page->firstRow.','.$Page->listRows)->select();
    	$this->assign('blackcom',$list);// 赋值数据集
    	$this->assign('blackpage',$show);// 赋值分页输出


		$this->display();
		//echo $ip = get_client_ip();
    }
    //形象监督
    		//红榜
    public function redcom(){
    	$committee = M('committee');
    	$count = $committee->where('status=1 and type=1')->count();// 查询满足要求的总记录数
    	$Page  = new \Think\Page($count,12);// 实例化分页类 传入总记录数和每页显示的记录数(25)
    	$show  = $Page->show();// 分页显示输出
    	// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
    	$list = $committee->field('img,title,instime')->where('status=1 and type=1')->order('instime')->limit($Page->firstRow.','.$Page->listRows)->select();
    	foreach($list as $key=>$val){
    		$list[$key]['instime'] = date('Y-m-d H:i:s',$val['instime']);
    	}
    	$data['page'] = ceil($count/12);
    	$data['data'] = $list;
    	if($list === false){
			ajaxReturn(101,'请求失败','');
    	}else{
             if(!$data['data']){
                $data['data'] = array();
            }
    		ajaxReturn(0,'',$data);
    	}

    }
    //形象监督
    		//黑榜
    public function blackcom(){
    	$committee = M('committee');
    	$count = $committee->where('status=1 and type=2')->count();// 查询满足要求的总记录数
    	$Page  = new \Think\Page($count,12);// 实例化分页类 传入总记录数和每页显示的记录数(25)
    	$show  = $Page->show();// 分页显示输出
    	// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
    	$list = $committee->field('img,title,instime')->where('status=1 and type=2')->order('instime')->limit($Page->firstRow.','.$Page->listRows)->select();
    	foreach($list as $key=>$val){
    		$list[$key]['instime'] = date('Y-m-d H:i:s',$val['instime']);
    	}
    	$data['page'] = ceil($count/12);
    	$data['data'] = $list;
    	if($list === false){
			ajaxReturn(101,'请求失败','');
    	}else{
            if(!$data['data']){
                $data['data'] = array();
            }
    		ajaxReturn(0,'',$data);
    	}
    }
    //演工委之声详情
    public function committee_details(){
        $id = I('get.id');
        $this->display();
    }
}