<?php
namespace Home\Controller;
use Think\Controller;
/**
 * 登录注册
 * @author winter
 * @version 2015年9月24日16:41:44
 */
class LoginController extends Controller {
    public function index(){
        $this->display();
    }
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
        $data['id'] = session('uid');
        $data['name'] = session('name');
        $user = M('user');
        $list = $user->where($data)->find();
        if(!$list){
            ajaxReturn(0,'未登录','');
        }else{
            ajaxReturn(1,'已登录','');
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
        if(IS_POST){
            session('user',null);
            $this->success(U('User/index'));
        }
    }
    
}