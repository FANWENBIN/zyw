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
        $user = M('user');
        $list = $user->where("openid='".$oid."'")->find();
        echo $user->getlastsql();
        if(!$list){
            $data['nickname'] = $uinfo['nickname'];
            $data['sex']      = $uinfo['gender'];
            $data['province'] = $uinfo['province'];
            $data['city']     = $uinfo['city'];
            $data['headpic']  = $uinfo['figureurl_2'];
            $data['openid']   = $oid;
            $data['passwd']   = md5('123456');
            $data['mobile']   = 'QQ';
            $data['createtime'] = time();
            $sign = $user->add($data);
            echo $user->getlastsql();
            $list = $user->where('id='.$sign)->find();
        }
        echo $user->getlastsql();
        session('userid',$sign['id']);
        session('username',$sign['nickname']);
        session('userphone',$sign['mobile']);
        session('userimg',$sign['headpic']);
        
        var_dump($uinfo);
    }
   //验证登陆接口
    public function checklogin(){
        //md5(xxzyw916);
        $data['id'] = session('userid');
        $data['mobile'] = session('userphone');
        $data['status'] = 1;
        $user = M('user');
        $list = $user->field('id,nickname,headpic,mobile,email,createtime,sex,province,city,birthday')->where($data)->find();
        if(!$list){
            session();
            ajaxReturn(0,'未登录','');
        }else{
            ajaxReturn(1,'已登录',$list);
        }
    }
    //登陆接口
    public function login(){
        $name = I('get.name');
        $passwd = I('get.passwd','','md5');
        $user = M('user');
        $data['mobile'] = $name;
        $data['passwd']   = $passwd;
        $data['status'] = 1;
        $sign =  $user->field('id,nickname,headpic,mobile,email,createtime,sex,province,city,birthday')->where($data)->find();
        if($sign){
            session('userid',$sign['id']);
            session('username',$sign['nickname']);
            session('userphone',$sign['mobile']);
            session('userimg',$sign['headpic']);
            ajaxReturn(1,'登陆成功',$sign);
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
        if($phone != session('phone')){
            ajaxReturn(102,'手机验证码输入错误','');
        }
        $password = I('get.passwd');
        preg_match("/^(?=.*[a-zA-Z])(?=.*[0-9])[a-zA-Z0-9]{8,}$/",$password,$array);
        if($array){
            ajaxReturn(105,'密码过于简单');
        }
        $user = M('user');
        $data['mobile'] = $phone;
        $data['passwd'] = $passwd;
        $data['nickname'] = $phone;
        $sum = $user->where('mobile='.$phone.'and status = 1')->count();
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
    //前台调手机验证码接口验证登陆
    public function yzm(){
        //调用短信先验证验证码是否正确
        $code = I('get.code');
        $a=$this->check_verify($code);
        if(!$a){
            ajaxReturn(102,'验证码输入错误','');
        }
        //随机生成验证码
        $ver = rand(100000,999999);
        $phone = I('get.phone');
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
    /**
     * 退出登录
     * @author winter
     * @version 2015年10月28日19:15:49
     */
    public function logout(){
            session(null);
            $this->redirect(U('Index/index'),'',0,'');
    }
    
}