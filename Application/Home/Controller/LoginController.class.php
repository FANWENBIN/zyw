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
        
        if(!$list){
            $data['nickname'] = $uinfo['nickname'];
            $data['sex']      = ($uinfo['gender'] == '男')?1:2;
            $data['province'] = $uinfo['province'];
            $data['city']     = $uinfo['city'];
            $data['headpic']  = $uinfo['figureurl_2'];
            $data['openid']   = $oid;
            $data['passwd']   = md5('123456');
            $data['mobile']   = 'QQ';
            $data['createtime'] = time();
            $sign = $user->add($data);
            if(!$sign){
                $this->error('登陆失败');
            }
            $list = $user->where('id='.$sign)->find();
        }
        if($list){
            session('userid',$list['id']);
            session('username',$list['nickname']);
            session('userphone',$list['mobile']);
            session('userimg',$list['headpic']);
            echo "<script>window.close();window.opener.location.reload()</script>";
            //$this->redirect('Index/index', '', 0, '页面跳转中...');
        }else{
            $this->error('登陆失败');
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
                    $data['nickname'] = $user_message['screen_name'];
                    $data['sex']      = ($user_message['gender'] == 'm')?1:2;
                    $address = explode(' ', $user_message['location']);
                    $data['province'] = $address[0];
                    $data['city']     = $address[1];
                    $data['headpic']  = $user_message['profile_image_url'];
                    $data['wbuid']    = $uid;
                    $data['passwd']   = md5('123456');
                    $data['mobile']   = 'WB';
                    $data['createtime'] = time();
                    $sign = $user->add($data);
                    if(!$sign){
                        $this->error('登陆失败');
                    }
                    $list = $user->where('id='.$sign)->find();
                }
                session('userid',$list['id']);
                session('username',$list['nickname']);
                session('userphone',$list['mobile']);
                session('userimg',$list['headpic']);
                echo "<script>window.close();window.opener.location.reload()</script>";

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
        $weixin = new \Home\Common\Weixin();
        $path = C('DOMAIN_PATH');
        $url = urlencode($path.'/index.php/Home/Login/weixincallback');
        $code_url = 'https://open.weixin.qq.com/connect/qrconnect?appid=wx891ba79c70766c9b&redirect_uri='.$url.'&response_type=code&scope=snsapi_login&state=STATE#wechat_redirect';
        echo $code_url;die();
        header("Location:".$code_url);
        
    }
    /**
    *微信回调
    */
    public function weixincallback(){
        var_dump(I('get.'));
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