<?php
namespace Stage\Controller;
use Think\Controller;
//粉丝团
class RiceController extends ComController {
  public function index(){
    $fans = M('fans_club');
    //$this->list = $fans->order('fanssum desc')->select();
    $data['status'] = 1;
    $count      = $fans->where($data)->count();// 查询满足要求的总记录数
    $Page       = new \Think\Page($count,25);// 实例化分页类 传入总记录数和每页显示的记录数(25)
    $show       = $Page->show();// 分页显示输出
    // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
    $list = $fans->where($data)->order('fanssum desc')->limit($Page->firstRow.','.$Page->listRows)->select();
    $this->assign('page',$show);// 赋值分页输出

    $this->list = $list;

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
        $id = $sign;
      }
      if($sign){
        $this->addadminlog($data['name'],$fans->getlastsql(),'删除/新增 饭团',$id,'riceid');
        $this->success('操作成功',U('index'));
      }else{
        $this->error('操作失败');
      }
    }
  }
  /**
  *删除论坛
  *@author winter
  *@version 2015年11月12日17:19:57
  */
  public function delete(){
    $id = I('post.id');
    $club = M('fans_club');
    $data['status'] = 0;
    $list = $club->where('id='.$id)->find();
    $sign = $club->where('id='.$id)->save($data);
    if($sign){
      $this->addadminlog($list['name'],$club->getlastsql(),'删除饭团',$id,'riceid');
      $this->success(U('index'),'删除成功');
    }else{
      $this->error('删除失败');
    }
  }
  /**
  *删除帖子
  *@author winter
  *@version 2015年11月12日17:39:22
  */
  public function postsdelete(){
    $id = I('post.id');
    $fans_posts = M('fans_posts');
    $data['status'] = 0;
    $list = $fans_posts->where('id='.$id)->find();
    $sign = $fans_posts->where('id='.$id)->save($data);
    if($sign){
      $this->addadminlog($list['title'],$fans_posts->getlastsql(),'删除帖子',$id,'riceid');
      $this->success(U('posts'),'删除成功');
    }else{
      $this->error('删除失败');
    }
  }
  /**
  *论坛帖子管理
  *@author winter
  *@version 2015年11月10日15:39:21
  */
  public function posts(){
    $fans_posts = M('fans_posts');
    $fans_club  = M('fans_club');

    //$postslist  = $fans_posts->order('instime desc')->select();
    $data['status'] = 1;
    $count = $fans_posts->where($data)->count();
    $page  = new \Think\Page($count,15);
    $show  = $page->show();
    $postslist = $fans_posts->where($data)->order('instime desc')->limit($page->firstRow.','.$page->listRows)->select();
    $this->page = $show;
    foreach ($postslist as $key => $value) {
      $clubval = $fans_club->where('id='.$value['fansclubid'])->find();
      $postslist[$key]['fansclubid'] = $clubval['name'];
    }
    

    $this->list = $postslist;
   
    $this->cur = 14;
    $this->display();
  }
  /*帖子修改*/
  public function postsedit(){
    $submit = I('post.submit');
    $id = I('get.id');
    $fans = M('fans_posts');
    $fans_club  = M('fans_club');
    $actors = M('actors');
    if(empty($submit)){
      if(!empty($id)){
        $where['id'] = $id;
        $list = $fans->where($where)->find();
      }
     $this->clubval = $fans_club->where('id='.$list['fansclubid'])->find();
     $this->list = $list;
      //$this->actor = $actors->field('id,name')->where('status <> 0 and status <> 3')->order('initial')->select();
      $this->cur = 14;
      $this->display();
    }else{
      $data['title']   = I('post.title');
      $data['content'] = I('post.content');

      preg_match_all('/src=&quot;(.*?(\.jpg|\.jpeg|\.gif|\.png))/',$data['content'], $strs);
        $data['img'] = '';
        foreach($strs[1] as $key=>$val){
            $data['img'] .= $val.',';               //加入图片
        }
      $id = I('get.id');
      
      $sign = $fans->where('id='.$id)->save($data);
      
      if($sign){
      $this->addadminlog($data['title'],$fans->getlastsql(),'修改帖子',$id,'riceid');
        $this->success('操作成功',U('posts'));
      }else{
        $this->error('操作失败');
      }
    }
  }
  /**
  *论坛评论回复
  *@author witner
  *@version 2015年11月10日15:42:09
  */
  public function comment(){
    $fans_comment = M('fans_comment');
     //$postslist  = $fans_posts->order('instime desc')->select();
    $data['status'] = 1;
    $count = $fans_comment->where($data)->count();
    $page  = new \Think\Page($count,15);
    $show  = $page->show();
    $postslist = $fans_comment->where($data)->order('instime desc')->limit($page->firstRow.','.$page->listRows)->select();
    $this->page = $show;
    $this->list = $postslist;
    
    $this->cur = 14;
    $this->display();
  }
  /**
  *删除论坛评论回复
  *@author  winter
  *@version 2015年11月12日19:36:02
  */
  public function commentdelete(){
    $fans_comment = M('fans_comment');
    $id = I('post.id');
    $data['status'] = 0;
    $list = $fans_comment->where('id='.$id)->find();
    $sign = $fans_comment->where('id='.$id)->save($data);
    if($sign){
      $this->addadminlog($list['title'],$fans_comment->getlastsql(),'删除论坛评论',$id,'riceid');
      $this->success(U('comment'),'删除成功');
    }else{
      $this->error('删除失败');
    }
  }
  /**
  *待审核粉丝团列表
  *@author winter
  *@version 2015年11月17日14:07:43
  */
  public function auditlist(){
    $fans = M('fans_club');
    //$this->list = $fans->order('fanssum desc')->select();
    $data['status'] = 2;
    $count      = $fans->where($data)->count();// 查询满足要求的总记录数
    $Page       = new \Think\Page($count,25);// 实例化分页类 传入总记录数和每页显示的记录数(25)
    $show       = $Page->show();// 分页显示输出
    // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
    $list = $fans->where($data)->order('fanssum desc')->limit($Page->firstRow.','.$Page->listRows)->select();
    $this->assign('page',$show);// 赋值分页输出
     
    $this->list = $list;

    $this->cur = 14;
    $this->display();
  }
  public function auditdelete(){
    $id = I('post.id');
    $club = M('fans_club');
    $data['status'] = 0;
    $list = $club->where('id='.$id)->find();
    $sign = $club->where('id='.$id)->save($data);
    if($sign){
    $this->addadminlog($list['name'],$club->getlastsql(),'删除待审核粉丝团',$id,'riceid');
      $this->success(U('index'),'删除成功');
    }else{
      $this->error('删除失败');
    }
  }
  /**
  *审核 修改
  */
  public function auditedit(){
    $submit = I('post.submit');
    $id = I('get.id');
    $fans = M('fans_club');
    $actors = M('actors');
    if(empty($submit)){
      if(!empty($id)){
        $where['id'] = $id;
        $list = $fans->where($where)->find();
        $this->list = $list;
      }
      $this->actor = $actors->field('id,name')->where('status <> 0 and status <> 3')->order('initial')->select();
      $this->cur = 14;
      $this->display();
    }else{
      $id = I('get.id');
      $audit = I('post.audit');

      if($audit == 0){
        $data['status'] = 0;
        $sign = $fans->where('id='.$id)->save($data);
        if($sign){
          $this->success('操作成功',U('auditlist'));
        }else{
          $this->error('操作失败');
        }
      }else{
        $data['status'] = 1;
        $data['actorid'] = I('post.actorid');
        $data['name']    = I('post.name');
        $data['img']     = I('post.img');
        
        if(!empty($id)){
          $sign = $fans->where('id='.$id)->save($data);
        }else{
          $sign = $fans->add($data);
          $id = $sign;
        }
        if($sign){
          $this->addadminlog($data['name'],$fans->getlastsql(),'删除待审核粉丝团',$id,'riceid');
          $this->success('操作成功',U('auditlist'));
        }else{
          $this->error('操作失败');
        }
      }
    }
  }
}