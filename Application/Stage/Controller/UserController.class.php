<?php
namespace Stage\Controller;
use Think\Controller;

/**
 * 用户管理
 * @author hxf
 * @version 2015年9月23日16:05:24
 */
class UserController extends ComController {
    
    /**
     * 列表
     * @author hxf
     * @version 2015年9月23日16:05:24
     */
    public function index()
    {
        $user = M("User");
        $count     = $user->where(array('status'=>'1'))->count();
        $Page      = new \Think\Page($count,10);
        $this->page= $Page->show();
        //用户列表
        $this->list = $user->where(array('status'=>'1'))->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('cur',1);
        $this->display();
    }
    
    /**
     * 查看
     * @author hxf
     * @version 2015年9月23日16:05:24
     */
    public function look()
    {
        $id = I('get.id',0,'intval');
        //用户信息
        $this->user = M("User")->where(array('status'=>'1','id'=>$id))->find();
        empty($this->user)&&$this->error('未找到此数据');
        $this->assign('cur',1);
        $this->display();
    }
    
    /**
     * 编辑
     * @author hxf
     * @version 2015年9月23日16:05:24
     */
    public function edit()
    {
        $id = I('get.id',0,'intval');
        if(IS_POST){
            $data = array(
                'nickname' => I('post.nickname'),
                'sex'      => I('post.sex',1,'intval'),
                'mobile'   => I('post.mobile'),
                'address'  => I('post.address'),
                'birthday' => strtotime(str_replace('|', ' ', $_POST['birthday'])),
                'email'    => I('post.email'),
            );
            $status = M("User")->where(array('status'=>'1','id'=>$id))->save($data);
            if($status === false)
            {
                $this->error('操作失败!'); 
            }else{
                $this->success('操作成功!',U('index')); 
            }
        }else{
            //用户信息
            $this->user = M("User")->where(array('status'=>'1','id'=>$id))->find();
            empty($this->user)&&$this->error('未找到此数据');
            $this->assign('cur',1);
            $this->display();
        }  
    }
    
    /**
     * 删除
     * @author hxf
     * @version 2015年9月23日16:05:24
     */
    public function delete()
    {
        $id = I('post.id',0,'intval');
        $user = M("User")->where(array('id'=>$id))->find();
        empty($user)&&$this->error('未找到此数据');
        //删除
        M("User")->where(array('id'=>$id))->save(array('status'=>'0'));
        $this->success('操作成功!');        
    }
    /**
    *撤回消息
    *@author：winter
    *@version:2015年11月3日13:22:16
    */
    public function deletesms(){
        $id = I('post.id',0,'intval');
        $user = M("user_msg")->where(array('id'=>$id))->find();
       // var_dump($id);die();
        empty($user)&&$this->error('未找到此数据');
        //删除
        M("user_msg")->where(array('id'=>$id))->save(array('status'=>'0'));
        $this->success('操作成功!');  
    }

    /*给用户发送消息
    author:winter
    date：2015年11月3日11:32:55
    添加用户接受消息表
    */
    public function sendmsg(){
        $submit = I('post.submit');
        if(empty($submit)){
            $id = I('get.id','','intval');
            $this->user = M("User")->where(array('status'=>'1','id'=>$id))->find();
            empty($this->user)&&$this->error('未找到此数据');
            $this->time = date('Y-m-d|H:i');
            $this->assign('cur',1);
            $this->display();
        }else{
            $id = I('get.id','','intval');
            $user_msg = M('user_msg');
            $data['type'] = 1;
            $data['msg']  = I('post.content');
            $data['instime'] = strtotime(I('post.instime'));
            $data['status'] = 2;
            $data['uid'] = $id;
            $data['username'] = I('post.nickname');
            $sign = $user_msg->add($data);
            if($sign){
                $this->success('发送成功',U('User/index'));
            }else{
                $this->error('消息未发送');
            }

        }
    }
    /**
    * @note 用户消息管理
    * @author ：winter
    * @date :2015年11月3日12:07:17
    */
    public function usermsg(){
        $user_msg = M('user_msg');
        $condition['type'] = 1;
        $condition['status'] = array('neq',0);
        //$list = $user_msg->where()->order('instime')->select();

        $count      = $user_msg->where($condition)->count();
        // 查询满足要求的总记录数
        $Page       = new \Think\Page($count,10);
        // 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show       = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $user_msg->where($condition)->order('instime')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->display();
    }
    /**
    *@note:消息详情
    *@author：winter
    *@version :2015年11月3日13:28:03
    */
    public function smsinfo(){
        $id = I('get.id','','trim');
        $user_msg = M('user_msg');
        $list = $user_msg->where('id='.$id)->find();
        empty($list)&&$this->error('未找到该数据');
        $this->assign('list',$list);
        $this->display();
    }
    
}