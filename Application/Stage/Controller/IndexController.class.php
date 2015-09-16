<?php
namespace Stage\Controller;
use Think\Controller;
//首页类
class IndexController extends ComController {
    public function index (){
        

        $submit= I('post.submit');
        if(empty($submit)){
            
            $this->assign('verify',$verify);
            $this->display('login');
        }else{
        $code=I('post.code');
            $a=$this->check_verify($code);
            if($a){
                $data['name']= I('post.user');
                $data['passwd']=md5(I('post.pw'));
                
                $user=M('admin');
           
                $a=$user->where($data)->select();
                //echo $user->getlastsql();die();
                if($a){
                    $uid = md5('xxxzyw916');
                    session('uid',$uid);
                    $this->success('登陆成功',U('Index/show'),5);
                    //$this->redirect('New/category', array('cate_id' => 2), 5, '页面跳转中...');
                }else{
                    $this->error('登陆失败',U('Index/index'),5);
                }
                
            }else{
                $this->error('验证码输入错误',U('Index/index'),5);
            }
            
        }
        
    }
    //验证码
    public function verify(){
        $Verify = new \Think\Verify();
        $Verify->entry();
    }

    function check_verify($code){  
    $verify = new \Think\Verify();   
    return $verify->check($code);
    }
    public function show(){
        $this->vercklogin();
        $this->display('index');
        //echo md5('xxxzyw916');        
    }

}