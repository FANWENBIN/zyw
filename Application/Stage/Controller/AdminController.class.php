<?php
namespace Stage\Controller;
use Think\Controller;
//管理员页面
class AdminController extends Controller {
    //
    public function index (){
        $admin = M('admin');
        $this->list = $admin->select();
       
        $this->assign('cur',0);
        $this->display();
    }
   
    /**
    *删除账号
    *@version 2015年11月10日13:28:14
    *@author winter
    */
    public function delete(){
        $id = I('post.id');
        $admin = M('admin');
        $sign = $admin->delete($id);
        if($sign){
            $this->success('删除成功');
        }else{
            $this->error('删除失败');
        }
    }
    /**
    *新增管理员
    *@version 2015年11月10日13:29:11
    *@author winter
    */
    public function add(){
        $submit = I('post.submit');
        if(empty($submit)){
            $this->cur = 0;
            $this->display();
        }else{
            $data['name'] = I('post.name');
            $data['passwd'] = I('post.passwd','','md5');
            $data['instime'] = time();
            $admin = M('admin');
            $sign = $admin->add($data);
            if($sign){
                $this->success('新增成功',U('index'));
            }else{
                $this->error('新增失败',1);
            }
        }
       
    }

}