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
     * @param string account 账号
     * @param string passwd  密码
     * @version 2015年9月24日16:41:57
     */
    public function login(){
        $account = I('get.account');
        $passwd  = I('get.passwd');
        (empty($account)||empty($passwd))&&$this->ajaxReturn(array('status'=>'1000','msg'=>'帐号密码输入不正确','data'=>null));
        $user = M("User")->where("mobile='".$account."' or email='".$account."'")->find();
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
     * @param string type        注册类型 mobile 手机号 email 邮箱
     * @param string mobile      手机号码
     * @param string email       邮箱 
     * @param string passwd      密码 
     * @param string mobile_code 手机验证码 
     * @param string code        验证码
     * @version 2015年9月24日16:41:57
     */
    public function register(){
        (check_verify(I('get.code'))) || $this->error('验证码输入错误！');
        $type                = I('get.type'); 
        //注册类型
        switch ($type)
        {
        //手机注册
        case 'mobile':
            $mobile              = I('get.mobile');
            $passwd              = I('get.passwd');
            $mobile_code         = I('get.mobile_code');
            $session_mobile_code = session('session_mobile_code');   //手机验证码
            //判断手机验证码
            if($mobile_code == $session_mobile_code)
            {
                $this->ajaxReturn(array('status'=>'1001','msg'=>'手机验证码不正确','data'=>null));
            }
            if(empty($mobile)||empty($passwd))
            {
                $this->ajaxReturn(array('status'=>'1004','msg'=>'请输入手机号和密码','data'=>null));
            }
            //判断用户是否存在
            $user = M("User")->where(array('mobile'=>$mobile))->find();
            if($user && $user['status'] == 0)
            {   
                //用户已被注销
                $data = array(
                    'passwd' => md5($passwd),
                    'status' => '1',
                );
                $status = M("User")->where(array('mobile'=>$mobile))->save($data);
            }else if($user && $user['status'] == 1)
            {
                //用户已存在
                $this->ajaxReturn(array('status'=>'1002','msg'=>'该用户已存在','data'=>null));
            }else{
                //用户不存在
                $data = array(
                    'mobile'   => $mobile,
                    'nickname' => $mobile,
                    'passwd'   => md5($passwd),
                    'status'   => '1',
                );
                $status = M("User")->add($data);
            }
            break; 
        //邮箱注册 
        case 'email':
            $email               = I('get.email');
            $passwd              = I('get.passwd');
            if(empty($email)||empty($passwd))
            {
                $this->ajaxReturn(array('status'=>'1005','msg'=>'请输入邮箱和密码','data'=>null));
            }
            //判断用户是否存在
            $user = M("User")->where(array('email'=>$email))->find();
            if($user && $user['status'] == 0)
            {   
                //用户已被注销
                $data = array(
                    'passwd' => md5($passwd),
                    'status' => '1',
                );
                $status = M("User")->where(array('email'=>$email))->save($data);
            }else if($user && $user['status'] == 1)
            {
                //用户已存在
                $this->ajaxReturn(array('status'=>'1002','msg'=>'该用户已存在','data'=>null));
            }else{
                //用户不存在
                $data = array(
                    'email'    => $email,
                    'nickname' => $email,
                    'passwd'   => md5($passwd),
                    'status'   => '1',
                );
                $status = M("User")->add($data);
            }
            break;
        //其他
        default:
            $this->ajaxReturn(array('status'=>'1000','msg'=>'注册类型不正确','data'=>null));
            break;
        }
        if($status === false || !$status)
        {
            $this->ajaxReturn(array('status'=>'1003','msg'=>'注册失败','data'=>null));
        }else{
            $this->ajaxReturn(array('status'=>'1','msg'=>'注册成功','data'=>null));
        }   
    }
    
    
     /**
     * 验证码
     * @author hxf
     * @version 2015年9月24日16:41:57
     */
    public function verify(){
        $verify = new \Think\Verify(array('imageH'=>40,'imageW'=>140,'length'=>4,'fontSize'=>18,'useNoise'=>false,'expire'=>1800));
        $verify->entry(1);
    }
    
    /**
     * 退出登录
     * @author huqinlou
     * @version 2015年8月18日 下午12:54:11
     */
    public function logout(){
        if(IS_POST){
            session('user',null);
            $this->success(U('User/index'));
        }
    }
    
}