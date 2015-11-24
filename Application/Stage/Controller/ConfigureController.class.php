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
                $this->addadminlog('备案号修改',$configure->getlastsql(),'备案号修改',4,'Configureid');
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
                $this->addadminlog('logo设置',$configure->getlastsql(),'logo设置',4,'Configureid');
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
                $this->addadminlog('报名规则修改',$configure->getlastsql(),'报名规则修改',1,'Configureid');
                $this->success('保存成功',U('Configure/rule'));
            }else{
                $this->error('未保存');
            }
        }  
    }
    //导航栏管理
    public function nav(){
        $nav = M('nav');
        $nav->startTrans();
        $sign = 1;
        $submit = I('post.submit');
        if(empty($submit)){
            $list = $nav->order('place')->select();
            $this->assign('list',$list);
            $this->assign('cur',12);
            $this->display();
        }else{
            $place = I('post.place');
            $name  = I('post.name');

            if (count($place) != count(array_unique($place))) {   
               $this->error('排序不可一样');
            }

            foreach ($place as $key => $value) {
                $data['id'] = $key;
                $data['place'] = $value;
                $data['name']  = $name[$key];
                $du = $nav->save($data);
                if($du === false){
                    $sign = 0;
                }
            }

            if($sign == 1){
                $nav->commit();
            $this->addadminlog('管理导航栏修改',$nav->getlastsql(),'管理导航栏修改',$data['id'],'Configureid');
                $this->success('保存成功',U('nav'));
            }else{
                $nav->rollback();
                $this->error('保存失败',U('nav'));
            }
        }
    }
    public function change(){
        $id = I('get.id');
        $data['status'] = intval(trim(I('get.status')));
        $nav = M('nav');
        $sign = $nav->where('id='.$id)->save($data);
        if($sign){
            $this->addadminlog('管理导航栏修改',$nav->getlastsql(),'管理导航栏修改',$id,'Configureid');
            $this->redirect(U('nav'),'',0,'');
        }else{
            $this->error('修改失败');
        }
    }
}