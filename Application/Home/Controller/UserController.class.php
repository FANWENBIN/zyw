<?php
namespace Home\Controller;
use Think\Controller;
/**
 * 用户中心
 * @author winter
 * @version 2015年11月2日16:38:31
 */
class UserController extends Controller {
    //手机绑定
    public function phonebinding(){
        $user = M('user');
        $userval = $user->find(session('userid'));
        $this->assign();
    }
    /**
	*我的活动，活动浏览记录以及发起的活动
	*@author：winter
	*@version：2015年11月3日16:58:12
	*
    */
    public function acthis(){
    	$acthis = M('acthis');
    	$data['uid'] = session('userid');       
    	//用户浏览活动记录，只记录最近的三个，数量由添加记录时控制
    	$this->$list = $acthis->where($data)->order('instime desc')->select();
    	//用户发起的活动
    		//待审核的活动
    	$active = M('active');
    	$data['status'] = 2;
    	$this->checkpending = $active->where($data)->order('instime desc')->select();
    		//审核通过的活动
    	$data['status'] = 1;
    	$this->actlist = $active->where($data)->order('instime desc')->select();
    	$this->display();
    }
    /**
    *我的消息，包括论坛消息和系统消息
    *@author:winter
    *@version:2015年11月3日17:08:03
    */
    public function mysms(){
    //显示status= 1或者2 的系统消息和论坛消息，时间戳要大于此时的，为了定时消息
    	$msg = M('user_msg');
    	$data['status'] = array('neq',0);
    	$data['instime'] = array('elt',time());
    	$data['uid'] = session('userid');
    	$data['type'] = 1; //系统消息
    	$this->syslist = $msg->where($data)->order('instime desc')->select();
    	$data['type'] = 2; // 帖子评论回复消息
    	$this->uselist = $msg->where($data)->order('instime desc')->select();
    	$this->display();
    }

}