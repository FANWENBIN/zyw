<?php
// 评论发起，和请求评论列表
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
            $param = getUrlParam($data['pagehref']);  //取出地址链接作为数组
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
    /**
    *评论分页显示数据调用
    *@param
    *@author witner
    *@version 2015年11月9日15:37:37
    *@return 
    */
    public function commentlist(){
        //评论显示
        $comment = M('comment');
        $data['status'] = 1;
        $type = trim(I('get.type'));
        $id = trim(I('get.id'));
        if(empty($type) || empty($id)){
            ajaxReturn(102,'参数错误','');
        }
        $data['typeid'] = $type;
        $data['pageid'] = $id;
        //$commentlist = $comment->field('id,name,namehead,content,instime,')->where($data)->select();
        $count = $comment->where($data)->count(); // 查询满足要求的总记录数
        $Page  = new \Think\Page($count,5); // 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show  = $Page->show(); //分页显示输出
        // 进行分页数据查询注意limit方法的参数要使用Page类的属性
        $list = $comment->field('id,name,namehead,content,instime')->where($data)->order('instime desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        //$this->assign('lists',$list); //赋值数据集
       // $this->assign('page',$show); //赋值分页输出
        $page = ceil($count/5);
        if(false === $list){
            ajaxReturn(101,'请求失败，重新请求','');
        }else{
            if(!$list){
                $list = array();
            }else{
                foreach ($list as $key => $value) {
                    $list[$key]['instime'] = date('Y/m/d H:i:s',$value['instime']);
                }
            }
            $data = array('data'=>$list,'page'=>$page);
            ajaxReturn(0,'',$data);
        }

    }

   
   
}