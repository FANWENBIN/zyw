<?php
namespace Home\Controller;
use Think\Controller;

/**
 * 登录注册
 * @author hxf
 * @version 2015年9月24日16:41:44
 */
class LoginController extends Controller {
    
    
    /**
     * 登录
     * @author hxf
     * @version 2015年9月24日16:41:57
     */
    public function login(){
        $account = I('get.account');
        $passwd  = I('get.passwd');
        (empty($account)||empty($passwd))&&$this->ajaxReturn(array('status'=>'1000','msg'=>'帐号密码输入不正确','data'=>null));
        $user = M("User")->where('(mobile='.$account.' or email='.$account.')')->find();
        $user||$this->ajaxReturn(array('status'=>'1001','msg'=>'帐号或密码输入错误！','data'=>null));
        if($user['status']!=1){
            $this->ajaxReturn(array('status'=>'1002','msg'=>'帐号已锁定！','data'=>null));
        }
        if(md5($passwd)!=$user['passwd']){  
            $this->ajaxReturn(array('status'=>'1003','msg'=>'帐号或密码输入错误！','data'=>null));
        }else{
            unset($user['passwd']);  
            session('user',$user); 
            $this->ajaxReturn(array('status'=>'1','msg'=>'登录成功','data'=>null));
        }
       
    }
    
    /**
     * 注册
     * @author hxf
     * @version 2015年9月24日16:41:57
     */
    public function register(){
        if(IS_POST)
        {
            
        }else{
            $this->display();
        }
    }
}