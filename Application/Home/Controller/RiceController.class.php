<?php
namespace Home\Controller;
use Think\Controller;
//粉丝饭团类
class RiceController extends ComController {
    public function index(){
    	$this->assign('sign',6);
    	//粉丝社群
    	$fans_club = M('fans_club');
    		//人数
    	//$where[''] =  '';
    	//$this->$perlist = $fans_club->order('fanssum desc')->limit(0,8)->select();
    	$perlist = $fans_club->order('fanssum desc')->limit(0,8)->select();
    	$this->assign('perlist',$perlist);
    	//var_dump($perlist);
    		//人气
    	$fanlist = $fans_club->order('readers desc')->limit(0,8)->select();
    	$this->assign('fanlist',$fanlist);
    		//活跃
    	$actlist = $fans_club->order('poststime desc')->limit(0,8)->select();
    	$this->assign('actlist',$actlist);
    		//banner
    	$this->banner = M('banner')->where('type = 9')->select();
    	$banner = M('banner');
        $this->display();
    }
    /**
	*粉丝更多页面
	*@author winter
	*@version 2015年11月6日19:43:28
    */
    public function riceall(){
    	$this->display();
    }
    /**
    *粉丝团所有接口数据
    *@author winter
    *@version 2015年11月6日19:40:54
    */
    public function morefans(){
    	$condition = I('get.condition');
    	$fans_club = M('fans_club');
    	//验证参数
    	$condition == 'fanssum' || $condition == 'readers' || $condition == 'poststime' || ajaxReturn(102,'参数错误','');
    	$order = $condition.' desc';
    	$count      = $fans_club->count();// 查询满足要求的总记录数
    	$Page       = new \Think\Page($count,8);// 实例化分页类 传入总记录数和每页显示的记录数(25)
    	$show       = $Page->show();// 分页显示输出
    	// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
    	$list = $fans_club->order($order)->limit($Page->firstRow.','.$Page->listRows)->select();
    	$this->assign('list',$list);// 赋值数据集
    	$this->assign('page',$show);// 赋值分页输出

    	$page = ceil($count/8);
    	if($list === false){
    		ajaxReturn(101,'请求失败，','');
    	}else{
    		if(!$list){
    			$list = array();
    		}
    		$data = array('data'=>$list,'page'=>$page);
    		ajaxReturn(0,'',$data);
    	}

    }
    /**
	*粉丝饭团详情页
	*@author winter
	*@version 2015年11月9日13:48:40
    */
    public function homepage(){
    	$this->display();
    }


}