<?php
namespace Stage\Controller;
use Think\Controller;
//首页类
class IndexController extends ComController {
    //登陆 zyw916
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
                    session('name',$data['name']);
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
    //退出
    public function loginout(){
       session(null);
       $this->success('安全退出',U('Index/index'));
    }
    //修改密码
    public function uppasswd(){
        $oldp = I('post.oldp','','md5');
        $data['newp'] = I('post.newp','','md5');
        $admin = M('admin');
        $where = array(
            'name'   =>session('name'),
            'passwd' =>$oldp
            );
        $adminval = $admin->where($where)->find();
        if($adminval){
            $sign = $admin->where('id='.$adminval['id'])->save($data);

            if($sign){
                ajaxReturn(0,'修改成功','');
            }else{
                ajaxReturn(1,'未修改成功',$admin->getlastsql());
            }
            
        }else{
            ajaxReturn(102,'旧密码不正确',$data['newp']);
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
    //首页显示
    public function show(){
        
        $this->vercklogin();  
        $user = M('user');
        $user->select();
   
        $this->display('index');
            
    }
    public function starmanage(){
        $this->display();
    }
    

}