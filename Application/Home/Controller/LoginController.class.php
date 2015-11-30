<?php
namespace Home\Controller;
use Think\Controller;
/**
 * 登录注册
 * @author winter
 * @version 2015年9月24日16:41:44
 */
class LoginController extends ComController {
    /**
    *QQ登陆    
    */
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

        if($list){
            session('userid',$list['id']);
            session('username',$list['nickname']);
            session('userphone',$list['mobile']);
            session('userimg',$list['headpic']);
            //var_dump($list);die();
            echo "<script>window.close();window.opener.location.reload()</script>";
            exit();
            //$this->redirect('Index/index', '', 0, '页面跳转中...');
        }else{
            
                session('uinfo',$uinfo);
                session('sign','QQ账号'); 
            echo "<script>window.close();window.opener.location.href='".U('User/threepartlogin')."'</script>";
            exit();
        }  
    }
    /**
    *微博登陆
    *@author winter
    *@version 2015年11月20日17:27:32
    */
    public function weibologin(){
        include_once( './libweibo/config.php' );
        include_once( './libweibo/saetv2.ex.class.php' );
        $o = new \SaeTOAuthV2( WB_AKEY , WB_SKEY );
        $code_url = $o->getAuthorizeURL( WB_CALLBACK_URL );
        header("Location:".$code_url);
    }
    /**
    *微博回调
    *@author winter
    *@version 2015年11月20日19:52:33
    */
    public function weibocallback(){
        include_once( './libweibo/config.php' );
        include_once( './libweibo/saetv2.ex.class.php' );
        $o = new \SaeTOAuthV2( WB_AKEY , WB_SKEY );
        if (isset($_REQUEST['code'])) {
            $keys = array();
            $keys['code'] = $_REQUEST['code'];
            $keys['redirect_uri'] = WB_CALLBACK_URL;
            try {
            $token = $o->getAccessToken( 'code', $keys ) ;
            } catch (OAuthException $e) {
            }
        }
        if ($token) {
            //授权完成
            $_SESSION['token'] = $token;
            setcookie( 'weibojs_'.$o->client_id, http_build_query($token) );
            $c = new \SaeTClientV2( WB_AKEY , WB_SKEY , $_SESSION['token']['access_token'] );
            //$ms  = $c->home_timeline(); // done
            $uid_get = $c->get_uid();
            $uid = $uid_get['uid'];
            $user_message = $c->show_user_by_id( $uid);//根据ID获取用户等基本信息
            if($user_message){
                $user = M('user');
                $list = $user->where("wbuid = '".$uid."'")->find();
                //echo $user->getlastsql();die();
                if(!$list){
                    session('uinfo',$user_message);
                    session('sign','微博账号');
            echo "<script>window.close();window.opener.location.href='".U('User/threepartlogin')."'</script>";
            exit();
                }
                session('userid',$list['id']);
                session('username',$list['nickname']);
                session('userphone',$list['mobile']);
                session('userimg',$list['headpic']);
                echo "<script>window.close();window.opener.location.reload()</script>";
                exit();
            }else{
                $this->error('登陆失败');
            }
        } else {
            //授权失败
            $this->error('登陆失败');
        }

    }
    /**
    *微信登陆
    */
    public function weixinlogin(){
        $path = C('DOMAIN_PATH');
        //$path = 'http://m2.nadoo.cn/p/zyw';
        $url = urlencode($path.'/index.php/Home/Login/weixincallback');
        $state = md5(md5('sxx123').rand(100,222));
        session('state',$state);
        $code_url = 'https://open.weixin.qq.com/connect/qrconnect?appid=wx891ba79c70766c9b&redirect_uri='.$url.'&response_type=code&scope=snsapi_login&state='.$state.'#wechat_redirect';

        header("Location:".$code_url);
        
    }
    /**
    *微信回调
    */
    public function weixincallback(){
        $code = I('get.code');
        $state = I('get.state');
        if($state == session('state')){
            $weixin = new \Home\Common\Weixin($code);
            $gettoken = $weixin->get_token();
            $token = $gettoken['access_token'];
            $openid = $gettoken['openid'];
            $user = M('user');
            $list = $user->where("openid = '".$openid."'")->find();
            if(!$list){
                $userinfo = $weixin->get_user_info($token,$openid);
                if(!empty($userinfo['sex'])){
                    session('uinfo',$userinfo);
                }
                session('sign','微信账号');
            echo "<script>window.close();window.opener.location.href='".U('User/threepartlogin')."'</script>";
                die();
            }
            session('userid',$list['id']);          //存储登陆信息
            session('username',$list['nickname']);
            session('userphone',$list['mobile']);
            session('userimg',$list['headpic']);
            echo "<script>window.close();window.opener.location.reload()</script>";    //关闭页面
            
        }else{
            $this->display('Public:404');
        }

    }
    /**
    *第三方登陆验证手机是否已注册
    *@author winter
    *@version 2015年11月30日11:31:53
    */
    public function checkphone(){
        $phone = I('get.phone');
        if(!preg_match("/1[3458]{1}\d{9}$/",$phone)){  
            ajaxReturn(103,'手机输入不符合格式');  
        }
        $user = M('user');
        $list = $user->where('mobile = '.$phone.' and status = 1')->find();
        if($list === false){
            ajaxReturn(102,'验证失败，重新验证','');
        }else{
            if($list){
                ajaxReturn(101,'手机号已被注册','');
            }else{
                session('checkphone',$phone);
                ajaxReturn(0,'通过','');
            }
            
        }
    }
    /**
    *第三方登录 发送验证码
    *@author winter
    *@version 2015年11月30日11:52:47
    */
    public function sendyzm(){

        //随机生成验证码
        $ver = rand(100000,999999);
        $phone = I('get.phone');
        if(!preg_match("/1[3458]{1}\d{9}$/",$phone)){  
            ajaxReturn(103,'手机输入不符合格式');  
        }
        if($phone != session('checkphone')){
            ajaxReturn(102,'手机号错误');
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
    *第三方注册
    * @author winter
    * @version 2015年11月30日13:36:16
    * @
    */
    public function thpareg(){
        $phone = I('post.phone');
        $passwd = I('post.passwd');
        $verify = I('post.verify');
        //var_dump(session('uinfo'));
        var_dump(session('uinfo'));
        if(!preg_match("/1[3458]{1}\d{9}$/",$phone)){  
            ajaxReturn(103,'手机输入不符合格式');  
        }
        preg_match("/^(?=.*[a-zA-Z])(?=.*[0-9])[a-zA-Z0-9]{8,}$/",$passwd,$array);
        if(!$array){
            ajaxReturn(105,'密码过于简单');
        }
        if($phone != session('phone') || $verify != session('yzm')){
            ajaxReturn(104,'验证码输入错误','');
        }



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
            session('userid','');
            session('username','');
            session('userphone','');
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
        //var_dump($array);
        if(!$array){
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