<?php
namespace Stage\Controller;
use Think\Controller;
//演艺库类
class ConfigureController extends ComController {
    //备案号设置
    public function index(){
        $submit = I('post.submit');
        $configure = M('configure');
        if(empty($submit)){
            $list = $configure->where('type = 4')->find();
            $this->assign('list',$list);
            $this->assign('cur',12);
            $this->display();
        }else{
            $data['records'] = I('post.records');
            $data['stagerule'] = I('post.stagerule');
            $sign = $configure->where('type = 4')->save($data);
            if($sign){
                $this->success('保存成功',U('Configure/index'));
            }else{
                $this->error('未保存');
            }
        }  
          
    }
    //logo设置
    public function logo(){
        $submit = I('post.submit');
        $configure = M('configure');
        if(empty($submit)){
            $list = $configure->where('type = 4')->find();
            $this->assign('list',$list);
            $this->assign('cur',12);
            $this->display();
        }else{
            $data['records'] = I('post.records');
            $sign = $configure->where('type = 4')->save($data);
            if($sign){
                $this->success('保存成功',U('Configure/index'));
            }else{
                $this->error('未保存');
            }
        }  
    }
    //报名规则
    public function rule(){
        $submit = I('post.submit');
        $configure = M('configure');
        if(empty($submit)){
            $list = $configure->where('type = 1')->find();
            $this->assign('list',$list);
            $this->assign('cur',12);
            $this->display();
        }else{
            $data['stagetitle'] = I('post.stagetitle');
            $data['stagerule'] = I('post.stagerule');
            $sign = $configure->where('type = 1')->save($data);
            if($sign){
                $this->success('保存成功',U('Configure/rule'));
            }else{
                $this->error('未保存');
            }
        }  
    }

}