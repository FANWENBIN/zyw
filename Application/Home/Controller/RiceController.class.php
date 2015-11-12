<?php
namespace Home\Controller;
use Think\Controller;
//粉丝饭团类
class RiceController extends ComController {
    public function index(){
    	$this->assign('sign',6);
    	//粉丝社群
    	$fans_club = M('fans_club');
    		//人数
    	//$where[''] =  '';
    	//$this->$perlist = $fans_club->order('fanssum desc')->limit(0,8)->select();
    	$perlist = $fans_club->order('fanssum desc')->limit(0,8)->select();
    	$this->assign('perlist',$perlist);
    	//var_dump($perlist);
    		//人气
    	$fanlist = $fans_club->order('readers desc')->limit(0,8)->select();
    	$this->assign('fanlist',$fanlist);
    		//活跃
    	$actlist = $fans_club->order('poststime desc')->limit(0,8)->select();
    	$this->assign('actlist',$actlist);
    		//banner 
    	$this->banner = M('banner')->where('type = 9')->select();
    	$banner = M('banner');
        $this->display();
    }
    /**
	*粉丝更多页面
	*@author winter
	*@version 2015年11月6日19:43:28
    */
    public function riceall(){
       // $this->alertinfo('info');
    	$this->display();
    }
    /**
    *粉丝团所有数据接口
    *@author winter
    *@version 2015年11月6日19:40:54
    */
    public function morefans(){
    	$condition = I('get.condition');
    	$fans_club = M('fans_club');
    	//验证参数
    	$condition == 'fanssum' || $condition == 'readers' || $condition == 'poststime' || ajaxReturn(102,'参数错误','');
    	$order = $condition.' desc';
    	$count      = $fans_club->count();// 查询满足要求的总记录数
    	$Page       = new \Think\Page($count,8);// 实例化分页类 传入总记录数和每页显示的记录数(25)
    	$show       = $Page->show();// 分页显示输出
    	// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
    	$list = $fans_club->order($order)->limit($Page->firstRow.','.$Page->listRows)->select();
    	$this->assign('list',$list);// 赋值数据集
    	$this->assign('page',$show);// 赋值分页输出

    	$page = ceil($count/8);
    	if($list === false){
    		ajaxReturn(101,'请求失败','');
    	}else{
    		if(!$list){
    			$list = array();
    		}
    		$data = array('data'=>$list,'page'=>$page);
    		ajaxReturn(0,'',$data);
    	}
    }
    /**
	*粉丝饭团详情页
	*@author winter
	*@version 2015年11月9日13:48:40
    */
    public function homepage(){
        $id = I('get.id');
        $fans_club = M('fans_club');
        $fans_posts = M('fans_posts');
        //饭团信息

        $this->list = $fans_club->where('id='.$id)->find();
        
        $fans_club->where('id='.$id)->setInc('readers',1);//阅读数加1
        //饭团帖子
        // $fans_posts->where('fansclubid='.$id)->select();

        $count      = $fans_posts->where('fansclubid='.$id)->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,4);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show       = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $fans_posts->where('fansclubid='.$id)->order('instime desc')->limit($Page->firstRow.','.$Page->listRows)->select();

        foreach($list as $key=>$value){
            $list[$key]['content'] = preg_replace('/&lt;img\s+src=&quot;(.*?\/attached.*?(\.jpg|\.jpeg|\.gif|\.png).*?&gt;)/', '', $value['content']);
        }
        $this->assign('posts',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        //推荐活动
        $active = M('active');
        $activeval = $active->where('status = 1')->order('`order` desc,instime desc,concern desc')->find();
        if(!$activeval){
            $this->assign('empty',1);
        }
        $this->assign('activeval',$activeval);
    	$this->display();
    }
    /**
    *帖子详情页
    *@author witner
    *@version 2015年11月10日19:59:05
    *@
    */
    public function post_details(){
        $id = I('get.id'); //帖子id
        $fans_club    = M('fans_club');   //论坛
        $fans_posts   = M('fans_posts');  //帖子
        $fans_comment = M('fans_comment'); //评论回复
        $postslist = $fans_posts->where('id = '.$id)->find(); //帖子详情
        $clublist  = $fans_club->where('id = '.$postslist['fansclubid'])->find(); //饭团详情

        $fans_posts->where('id='.$id)->setInc('readers',1);  //阅读数加1
        //序列化
        //$this->$count = $fans_comment->where('postid = '.$postslist['id'].' and status = 1')->count();//评论数量
        //$list = $this->sortOut($commentlist,0,0,'---','fid','id');
        //echo $fans_comment->getlastsql();
        $flist = $fans_comment->order('instime asc')->where('postid = '.$postslist['id'].' and status = 1 and fid = 0')->select();  //评论列表
        foreach ($flist as $key => $value) {
            $slist = $fans_comment->order('instime asc')->where('postid = '.$postslist['id'].' and status = 1 and fid = '.$value['id'])->select();
           
            $array[$key]['flist'] = $value;
            $array[$key]['slist'] = $slist;
        }
        var_dump($array);
        $this->list = $array;
        $this->postslist = $postslist;
        $this->clublist  = $clublist;
        //var_dump($val);
        $this->display();
    }
    /**
    *加入粉丝饭团接口
    *@author winter
    *@version 2015年11月10日16:14:21
    *@param 
    */
    public function joinfans(){
        $fans = M('fans_club');    //粉丝团
        $user_fans = M('user_fans'); //用户关注粉丝团表
        $data['fansid'] = I('get.fansid');
        $data['userid'] = session('userid');
        $data['instime'] = time();
        $dump = $this->checkDump($data);          //检查数据不为空
        if($dump == 0){ajaxReturn(102,'数据不可为空','');}
        //根据id查询粉丝团是否存在
        $fansval = $fans->where('id='.$data['fansid'])->count();
        if(!$fansval){
            ajaxReturn(103,'不存在该团','');
        }
        //根据俩个id查询用户是否已关注粉丝团
        $userval = $user_fans->where('fansid = '.$data['fansid'].' and userid='.$data['userid'])->count();
        if($userval){
            ajaxReturn(104,'用户已加入团内','');
        }
        $sign = $user_fans->add($data);
        if($sign){
            $fans->where('id='.$data['fansid'])->setInc('fanssum',1);//粉丝数加1
            ajaxReturn(0,'加入成功','');
        }else{
            ajaxReturn(101,'加入失败，重新加入','');
        }
    }
    /**
    *检测用户是否已加入
    *@author winter
    *@version 2015年11月11日19:12:23
    *@
    */
    public function checkjoin(){
        $user_fans = M('user_fans');
        $data['fansid'] = I('get.fansid');
        $data['userid'] = session('userid');
        //根据俩个id查询用户是否已关注粉丝团
        $userval = $user_fans->where('fansid = '.$data['fansid'].' and userid='.$data['userid'])->count();
        if($userval){
            ajaxReturn(1,'用户已加入团内','');
        }else{
            ajaxReturn(0,'未加入','');
        }
    }
    /**
    *发帖子接口
    *@author winter
    *@version winter
    *@param fansclubid 论坛id，title 帖子标题，content 帖子内容，
    *@return 状态码
    */
    public function postini(){
        $data['fansclubid'] = I('post.fansclubid');
        $data['username']   = session('username');
        $data['userid']     = session('userid');
        $data['title']      = I('post.title');
        $data['content']    = I('post.content');


        $data['instime']    = time();
        $data['headimg']    = session('userimg');
        $result = $this->checkDump($data);
        //ajaxReturn(102,'',$data);
        if($result == 0){ajaxReturn(102,'参数不可为空','');}
        preg_match_all('/src=&quot;(.*?\/attached.*?(\.jpg|\.jpeg|\.gif|\.png))/',$data['content'], $strs);
        $data['img'] = '';
        foreach($strs[1] as $key=>$val){
            $data['img'] .= $val.',';               //加入图片
        }
        $posts = M('fans_posts');
        $fans_club = M('fans_club');
        $sign = $posts->add($data);
        if($sign){
            $fans_club->where('id='.$data['fansclubid'])->setInc('posts',1);
            ajaxReturn(0,'发布成功','');
        }else{
            ajaxReturn(101,'','发帖失败');
        }
    } 
    /**
    *发布评论接口
    *@author winter
    *@version 2015年11月10日19:38:47
    *@param fid 被评论内容id，postid 帖子id，content 评论内容，
    */
    public function comment(){
        $data['userid'] = session('userid');
       
        $data['postid'] = I('post.postid');
        $data['content'] = I('post.content');
        $data['instime'] = time();
        $data['status']  = 1;
        $data['name'] = session('username');
        $data['headimg'] = session('userimg');
        $result = $this->checkDump($data);
        if($result == 0){ajaxReturn(102,'参数不可为空','');}
        $data['fid']    = I('post.fid'); 
        $fans_comment = M('fans_comment');     //论坛评论表
        $user_msg = M('user_msg');             //用户消息表
        $user = M('user');                    //用户表
        $fans_posts = M('fans_posts');  //评论表
        $sign = $fans_comment->add($data);
        if($sign){
            $fans_posts->where('id='.$data['postid'])->setInc('comments',1);  //评论数加1
            $fval = $fans_comment->where('id='.$data['fid'])->find(); //父级数据；
            $userval = $user->where('id='.$fval['userid'])->find(); //被评论者用户数据

            $msg['type'] = 2;
            $msg['msg']  = $data['content'];
            $msg['status'] = 2;
            $msg['instime'] = time();
            $msg['uid'] = $userval['id'];
            $msg['username'] = $userval['nickname'];
            $user_msg->add($msg);     //被评论者接收数据
            ajaxReturn(0,'评论成功','');
        }else{
            ajaxReturn(101,'评论失败','');
        }

    }

}
