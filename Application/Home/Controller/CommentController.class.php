<?php
// 本类由系统自动生成，仅供测试用途
namespace Home\Controller;
use Think\Controller;
class CommentController extends ComController {
    public function index(){
		$this->display();
    }
    //用户增加评论接口
    public function addcomment(){
    	$name = session('username');
    	$id   = session('userid');
    	if(empty($name) || empty($id)){
    		ajaxReturn('105','未登录','');   //
    	}else{
    		$data['name']     = session('username');
    		$data['nameid']   = session('userid');
    		$data['content']  = I('post.content');
    		$data['pagehref'] = I('post.href');
    		$data['instime']  = time();
    		$data['pagename'] = I('post.pagename');

            $sign = $this->checkDump($data);
    		if(!$sign){
    			ajaxReturn(102,'不可有空信息','');
    		}
            //检测路径的c参数值。
            $data['pagehref'] =  preg_replace('/amp;/', '', $data['pagehref']);
            $param = getUrlParam($data['pagehref']);
            switch ($param['c']) {
                case 'News':
                   $data['typeid'] = 1;
                    break;
                case 'Video':
                   $data['typeid'] = 2;
                    break;
                default: 
                    break;
            }
            $data['pageid'] = $param['id'];
    		$userval = M('user')->where('id='.session('userid'))->find();
    		$data['namehead'] = $userval['headpic'];           //查询用户头像
    		$comment = M('comment');
    		$addid = $comment->add($data);
    		if($addid){
    			ajaxReturn(0,'评论成功','');
    		}else{
    			ajaxReturn(101,'评论失败','');
    		}

      	}

    }
   
   
}