<?php
namespace Stage\Controller;
use Think\Controller;
//视频类
class StageController extends ComController {
    //首页显示
    public function index(){
        $stage = M('stageworks');
        $works = $stage->where('status = 1')->select();

        $this->assign('list',$works);
        $this->assign('cur',13);
        $this->display();
    }
    //修改视频
    public function edit(){
        $submit = I('post.submit');
        $stage  = M('stageworks');
        if(empty($submit)){
            $id = I('get.id');
            $this->assign('cur',13);
            $vedioval = $stage->where('id='.$id)->find();
            $this->assign('vedioval',$vedioval);
            $this->display();
        }else{
            $id = I('get.id');
            $data['title'] = I('post.title');
            $data['href']  = I('post.href');
            $data['img']   = I('post.img');
            $data['content'] = I('post.content');
            //var_dump($data);die();
            $sign = $this->checkDump($data);
            if(!$sign){
                $this->error('主要信息不可为空');
            }

            $sign = $stage->where('id='.$id)->save($data);
            if($sign){
                $this->success('修改成功',U('Stage/index'));
            }else{
                $this->error('未做任何修改');
            }

        }
    }
    //删除作品
    public function delete(){
        $id = I('post.id','','intval');
        $stage = M('stageworks');
        $data['status'] = 0;

        $sign = $stage->where('id='.$id)->save($data);
        if($sign){
            $this->success('删除成功',U('Vedio/index'));
        }else{
            $this->error('未删除');
        }
    }
    //新增数据
    public function add(){
        $submit = I('post.submit');
        $stage  = M('stageworks');
        if(empty($submit)){

            $this->assign('cur',13);

            $this->display();
        }else{
            $id = I('get.id');
            $data['title'] = I('post.title');
            $data['href']  = I('post.href');
            $data['img']   = I('post.img');
            $data['content'] = I('post.content');
            //var_dump($data);die();
            $sign = $this->checkDump($data);
            if(!$sign){
                $this->error('主要信息不可为空');
            }

            $sign = $stage->add($data);
            if($sign){
                $this->success('新增成功',U('Stage/index'));
            }else{
                $this->error('新增失败');
            }
        }
    }
    //审核作品列表
    public function audit(){
        $stage = M('stageworks');
        $works = $stage->where('status = 2')->select();
        $this->assign('list',$works);
        $this->assign('cur',13);
        $this->display();
    }
}