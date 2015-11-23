<?php
namespace Stage\Controller;
use Think\Controller;
//管理员页面
class AdminController extends ComController {
    //
    public function index (){
        $admin = M('admin');
        $this->list = $admin->where('id <> 1')->select();
       
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
            $this->addadminlog('管理员',$admin->getlastsql(),'删除管理员',$id,'admid');
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
                $this->addadminlog($data['name'],$admin->getlastsql(),'新增管理员',$sign,'admid');
                $this->success('新增成功',U('index'));
            }else{
                $this->error('新增失败',1);
            }
        }
       
    }
    /**
    *管理员操作日志
    *@author winter
    *@version 2015年11月23日16:25:21
    */
    public function adminloglist(){
        $adminlog = M('admin_log');
        $count      = $adminlog->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,25);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show       = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $adminlog->order('instime desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->display('adminloglist');
    }
    /**
    *操作详情
    *@author winter
    *@version 2015年11月23日16:44:13
    */
    public function adminloginfo(){
        $id = I('get.id');
        $adminlog = M('admin_log');
        $this->list = $adminlog->where('id='.$id)->find();
        $this->display('adminloginfo');
    }
    /**
    *管理员登陆登出时间
    *@author winter
    *@version 2015年11月23日20:14:58
    */
    public function online(){
        $online = M('admin_online');
       
        $count      = $online->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,25);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show       = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $online->order('instime desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出

        $this->display();

    }
}