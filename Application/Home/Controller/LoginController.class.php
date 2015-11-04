<?php
namespace Home\Controller;
use Think\Controller;
/**
 * 登录注册
 * @author winter
 * @version 2015年9月24日16:41:44
 */
class LoginController extends ComController {

    public function qqlogin(){
        require('QQ/API/qqConnectAPI.php');
        $qc = new \QC();
        $qc->qq_login();
    }
    public function callback(){
        require('QQ/API/qqConnectAPI.php');//引进qqapi 接口
        $qc = new \QC();  
        $acs = $qc->qq_callback();      //获取access-token 和openid
        $oid = $qc->get_openid();  
        $qc = new \QC($acs,$oid);  
        $uinfo = $qc->get_user_info();  //获取用户信息
        var_dump($uinfo);
    }
   //验证登陆接口
    public function checklogin(){
        //md5(xxzyw916);
        $data['id'] = session('userid');
        $data['name'] = session('username');
        $user = M('user');
        $list = $user->where($data)->find();
        if(!$list){
            ajaxReturn(0,'未登录','');
        }else{
            ajaxReturn(1,'已登录',$list);
        }
    }
    
    /**
    * 验证码
    * @author winter
    * @version 2015年10月28日19:15:25
    */
    public function verify(){
        ob_end_clean();
        $verify = new \Think\Verify(array('imageH'=>40,'imageW'=>140,'length'=>4,'fontSize'=>18,'useNoise'=>false,'expire'=>1800));
        $verify->entry();
    }
    function check_verify($code){  
    $verify = new \Think\Verify();   
    return $verify->check($code);
    }
    //登陆接口
    public function login(){
        $name = I('get.name');
        $passwd = I('get.passwd','','md5');
        $user = M('user');
        $data['nickname'] = $name;
        $data['passwd']   = $passwd;
        $sign =  $user->where($data)->find();
        if($sign){
            session('userid',$sign['id']);
            session('username',$sign['nickname']);
            session('userphone',$sign['mobile']);
            ajaxReturn(1,'登陆成功','');
        }else{
            ajaxReturn(0,'账号密码输入错误','');
        }
    }
    //注册接口
    public function register(){
        $phone = I('get.phone','','trim');
        $passwd = I('get.passwd','','md5');
        $verify = I('get.verify');
        if($verify != session('yzm')){
            ajaxReturn(102,'手机验证码输入错误','');
        }
        $user = M('user');
        $data['mobile'] = $phone;
        $data['passwd'] = $passwd;
        $data['nickname'] = $phone;
        $sum = $user->where('mobile='.$phone)->count();
        if($sum >= 1){
            ajaxReturn(103,'账号已被注册过','');
        }
        $data['createtime'] = time();
        $data['status'] = 1;
        $sign = $user->add($data);
        if($sign){
            ajaxReturn(0,'注册成功','');
        }else{
            ajaxReturn(101,'注册失败','');
        }
    }
    //前台调用验证码接口
    public function yzm(){
        //调用短信先验证验证码是否正确
        $code = I('get.code');
        $a=$this->check_verify($code);
        if(!$a){
            ajaxReturn(102,'验证码输入错误','');
        }
        //随机生成验证码
        $ver = rand(1000,9999);
        $phone = I('get.phone');
        if(!preg_match("/1[3458]{1}\d{9}$/",$phone)){  
            ajaxReturn(103,'手机输入不符合格式');  
        }
        $sign = $this->sms($phone,$ver);
        if($sign){
            ajaxReturn(101,'发送失败','');
        }else{
            session('yzm',$ver);
            ajaxReturn(0,'发送成功','');
        }
    }
    /**
     * 退出登录
     * @author winter
     * @version 2015年10月28日19:15:49
     */
    public function logout(){
        if(IS_POST){
            session(null);
            $this->success(U('User/index'));
        }
    }
    
}