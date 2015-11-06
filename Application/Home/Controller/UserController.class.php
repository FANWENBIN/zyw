<?php
namespace Home\Controller;
use Think\Controller;
/**
 * 用户中心
 * @author winter
 * @version 2015年11月2日16:38:31
 */
class UserController extends ComController {
    /**基本设置*/
    public function setting(){

        //映射用户信息
        $userinfo = $this->checkuserLogin();

        //城市地区
        $province = M('provinces')->select();
        if(!empty($userinfo['provinceid'])){
            $where['provinceid'] = $userinfo['provinceid'];
        }else{
            $where['provinceid'] = $province[0]['provinceid'];
        }
        $this->$cities = M('cities')->where($where)->select();
        $this->assign('userinfo',$userinfo);
        $this->display();
    }
    //手机更换绑定
    public function phonebinding(){
        $data['mobile'] = I('post.phone');
        $code = I('post.code');
        if($code != session('yzm')){
            ajaxReturn(101,'验证码输入错误');
        }
        if($data['mobile'] != session('phone')){
            ajaxReturn(105,'手机与接收验证码手机号不符合');
        }
        $userlist = $this->checkuserLogin(); //验证登陆，并返回登陆信息
        $user = M('user');
        if(!$userlist){
            ajaxReturn(102,'未登录','');
        }
        $sign = $user->where('id='.session('userid'))->save($data);
        if($sign === false){
            ajaxReturn(102,'绑定失败');
        }else{
            ajaxReturn(0,'绑定成功');
        }
    }
    /**
    *用户更换手机发送验证码
    * @version 2015年11月5日15:45:00
    * @author witner
    */
    public function yzm(){
        //调用短信先验证验证码是否正确
        //随机生成验证码
        $ver = rand(1000,9999);
        $phone = I('get.phone');
       // ajaxReturn($phone);
        if(!preg_match("/1[3458]{1}\d{9}$/",$phone)){  
            ajaxReturn(103,'手机输入不符合格式');  
        }
        $sign = $this->sms($phone,$ver);
        if($sign){
            ajaxReturn(101,'发送失败','');
        }else{
            session('yzm',$ver);
            session('phone',$phone);
            ajaxReturn(0,'发送成功','');
        }
    }
    /**
	*我的活动，活动浏览记录以及发起的活动
	* @author：winter
	* @version：2015年11月3日16:58:12
	*
    */
    public function acthis(){
    	$acthis = M('acthis');
    	$data['userid'] = session('userid');       
    	//用户浏览活动记录，只记录最近的三个，数量由添加记录时控制
    	$this->$list = $acthis->where($data)->order('instime desc')->select();
    	//用户发起的活动
    	$active = M('active');
    		//待审核的活动
    	$data['status'] = 2;
    	$this->checkpending = $active->where($data)->order('instime desc')->select();
    		//审核通过的活动
    	$data['status'] = 1;
    	$this->passactive = $active->where($data)->order('instime desc')->select();
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
    /**
	*我关注的饭团
	*@author：winter
	*@version:2015年11月3日19:45:10
    */
    public function confans(){
    	$userfans = M('user_fans');
    	$this->$val = $userfans->where('userid='.session('userid'))->order('instime desc')->select();
    	$this->display();
    }

}