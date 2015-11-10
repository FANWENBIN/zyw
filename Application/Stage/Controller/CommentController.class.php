<?php
namespace Stage\Controller;
use Think\Controller;
//评论类
class CommentController extends ComController {
    //首页显示
    public function index(){
        $comment = M('comment');

        $commentval = $comment->where('status = 1')->order('instime desc')->select();

        $count      = $comment->where('status = 1')->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show       = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $this->list = $comment->order('instime desc')->where('status = 1')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('page',$show);// 赋值分页输出

        //$this->assign('list',$commentval);
        $this->assign('cur',11);
        $this->display();
    }
    //删除评论
    public function delcomment(){
    	$id = I('get.id');
    	$comment = M('comment');
    	$data['status'] = 0;
    	$sign = $comment->where('id='.$id)->save($data);
    	if($sign){
    		$this->success('已将垃圾扔进回收站',U('Comment/index'));
    	}else{
    		$this->error('删除未成功');
    	}
    }
    //修改评论
    public function upcomment(){
    	
		$comment = M('comment');
    	$submit = I('post.submit');
    	if(empty($submit)){
    		$id = I('get.id');
    		$list = $comment->where('id = '.$id)->find();
    		$this->assign('list',$list);
            $this->assign('cur',11);
    		$this->display();
    	}else{
    		$data['content'] = I('post.content');
    		$data['name']    = I('post.name');
    		$id = I('post.id');
    		$sign = $comment->where('id='.$id)->save($data);
    		if($sign === false){
    			$this->error('未做修改');
    		}else{
    			$this->success('修改成功',U('Comment/index'));
    		}
    	}
    }
    
}