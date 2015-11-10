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
    		ajaxReturn(101,'请求失败','');
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
        $id = I('get.id');
        $fans_club = M('fans_club');
        $fans_posts = M('posts');
        //饭团信息
        $this->list = $fans_club->where('id='.$id)->find();
        //饭团帖子
        // $fans_posts->where('fansclubid='.$id)->select();

        $count      = $fans_posts->where('fansclubid='.$id)->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,4);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show       = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $fans_posts->where('fansclubid='.$id)->order('instime desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('posts',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        //推荐活动
        $active = M('active');
        $activeval = $active->where('status = 1')->order('`order` desc,instime desc,concern desc')->find();
        if(!$activeval){
            $this->assign('empty',1);
        }
        $this->assign('activeval',$activeval);
    	$this->display();
    }
    /**
    *加入饭团接口
    *@author winter
    *@version 2015年11月10日16:14:21
    *@param 
    */
    public function joinfans(){
        $fans = M('fans_club');    //粉丝团
        $user_fans = M('user_fans'); //用户关注粉丝团表
        $data['fansid'] = I('get.fansid');
        $data['userid'] = session('userid');
        $data['instime'] = time();
        $dump = $this->checkDump($data);          //检查数据不为空
        if($dump == 0){ajaxReturn(102,'数据不可为空','');}
        //根据id查询粉丝团是否存在
        $fansval = $fans->where('id='.$data['fansid'])->count();
        if(!$fansval){
            ajaxReturn(103,'不存在该团','');
        }
        //根据俩个id查询用户是否已关注粉丝团
        $userval = $user_fans->where('fansid = '.$data['fansid'].' and userid='.$data['userid'])->count();
        if(!$userval){
            ajaxReturn(104,'用户已加入团内','');
        }
        $sign = $user_fans->add($data);
        if($sign){
            ajaxReturn(0,'加入成功','');
        }else{
            ajaxReturn(101,'加入失败，重新加入','');
        }
    }


}