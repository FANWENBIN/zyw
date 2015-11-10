<?php
namespace Stage\Controller;
use Think\Controller;
//粉丝团
class RiceController extends Controller {
  public function index(){
    $fans = M('fans_club');
    $this->list = $fans->order('fanssum desc')->select();

    $count      = $fans->count();// 查询满足要求的总记录数
    $Page       = new \Think\Page($count,25);// 实例化分页类 传入总记录数和每页显示的记录数(25)
    $show       = $Page->show();// 分页显示输出
    // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
    $this->list = $fans->order('fanssum desc')->limit($Page->firstRow.','.$Page->listRows)->select();
    $this->assign('page',$show);// 赋值分页输出


    $this->cur = 14;
    $this->display();
  }
  /**
  *修改增加
  *@author winter
  *@version 2015年11月10日14:05:02
  *@return 
  */
  public function edit(){
    $submit = I('post.submit');
    $id = I('get.id');
    $fans = M('fans_club');
    $actors = M('actors');
    if(empty($submit)){
      if(!empty($id)){
        $where['id'] = $id;
        $this->list = $fans->where($where)->find();
      }
     
      $this->actor = $actors->field('id,name')->where('status <> 0 and status <> 3')->order('initial')->select();
      $this->cur = 14;
      $this->display();
    }else{
      $data['actorid'] = I('post.actorid');
      $data['name']    = I('post.name');
      $data['img']     = I('post.img');
      $id = I('get.id');
      if(!empty($id)){
        $sign = $fans->where('id='.$id)->save($data);
      }else{
        $sign = $fans->add($data);
      }
    
      if($sign){
        $this->success('操作成功',U('index'));
      }else{
        $this->error('操作失败');
      }
    }
  }
  /**
  *论坛帖子管理
  *@author winter
  *@version 2015年11月10日15:39:21
  */
  public function posts(){
    $this->cur = 14;
    $this->display();
  }

}