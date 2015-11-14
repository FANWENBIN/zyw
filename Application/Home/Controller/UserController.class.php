<?php
namespace Home\Controller;
use Think\Controller;
/**
 * 用户中心
 * @author winter
 * @version 2015年11月2日16:38:31
 */
class UserController extends ComController {
    /**基本设置*/
    public function setting(){
        $submit = I('post.submit');
        //用户信息
        $userinfo = $this->checkuserLogin();
        if(empty($submit)){
            //城市地区
            $province = M('provinces')->select();
            $this->assign('province',$province);
            if(!empty($userinfo['provinceid'])){
                $where['provinceid'] = $userinfo['provinceid'];
            }else{
                $where['provinceid'] = $province[0]['provinceid'];
            }
            $cities = M('cities')->where($where)->select();
            $this->assign('cities',$cities);
            $userinfo['mobile'] = preg_replace('/'.substr($userinfo['mobile'],3,4).'/','****',$userinfo['mobile']);   
            $this->assign('userinfo',$userinfo);
            $this->display();
        }else{
            $user = M('user');
            $data['nickname'] = I('post.nickname');
            $provinces  = explode('|', I('post.province'));
            $cities     = explode('|', I('post.cities'));
            $data['provinceid'] = $provinces[0];
            $data['province']   = $provinces[1];
            $data['city']   = $cities[0];
            $data['cityid'] = $cities[1];
            $data['birthday'] = strtotime(I('post.birthday'));
            $data['sex']    = I('post.sex');
            $sign = $user->where('id='.session('userid'))->save($data);
            if($sign === false){
                $this->redirect(U('setting'),'', 2, '修改失败');
            }else{
                session('username',$data['nickname']); //更换用户名
                $this->redirect(U('setting'),array('cate_id' => 2), 0, '');
            }
        }
    }
    /**
    *用户上传修改头像
    *
    */
    public function uploadimg(){
        $upload = new \Think\Upload();// 实例化上传类    
        $upload->maxSize   =     3145728 ;// 设置附件上传大小    
        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型    
        $upload->savePath  =      '/userimg/'; // 设置附件上传目录    // 上传文件     
        $info   =   $upload->upload();  
        
        if(!$info) {// 上传错误提示错误信息        
            $this->error($upload->getError());    
        }else{// 上传成功      
            $user = M('user');
            $data['headpic'] = $info['photo']['savepath'].$info['photo']['savename'];
            $sign = $user->where('id='.session('userid'))->save($data);
            if($sign === false){
                $this->error('修改失败！'); 
            }else{
                $this->redirect(U('setting'),'', 0, '修改成功');
            }
        }
            
    }
    //手机更换绑定
    public function phonebinding(){
        $data['mobile'] = I('post.phone');
        $code = I('post.code');
        if($code != session('yzm')){
            ajaxReturn(101,'验证码输入错误');
        }
        if($data['mobile'] != session('phone')){
            ajaxReturn(105,'手机与接收验证码手机号不符合');
        }
        $userlist = $this->checkuserLogin(); //验证登陆，并返回登陆信息
        $user = M('user');
        if(!$userlist){
            ajaxReturn(102,'未登录','');
        }
        $sign = $user->where('id='.session('userid'))->save($data);
        if($sign === false){
            ajaxReturn(102,'绑定失败');
        }else{
            session('userphone',$data['mobile']);
            ajaxReturn(0,'绑定成功');
        }
    }
    /**
    *更改密码
    *@author witner
    *@version 2015年11月12日19:45:30
    */
    public function modipasswd(){
        $oldpasswd = I('post.oldpasswd','','md5');
        $newpasswd = I('post.newpasswd','','md5');
        $user = M('user');
        $data['mobile'] = session('userphone');
        $data['passwd'] = $oldpasswd;
        //ajaxReturn(1,'',$data);
        $userlist = $user->where($data)->find();
        if($userlist){
            $newdump['passwd'] = $newpasswd;
            $sign = $user->where('id='.$userlist['id'])->save($newdump);
            if($sign){
                ajaxReturn(0,'修改成功','');
            }else{
                ajaxReturn(101,'修改失败','');
            }
        }else{
            ajaxReturn(102,'旧密码错误','');
        }
    }
    /**
    *验证手机号，和验证码
    */
    public function checkphver(){
        $phone   = I('get.phone');
        $version = I('get.version');
        if($phone != session('phone') || $version != session('yzm')){
            ajaxReturn(104,'验证码输入错误');
        }else{
            $user = M('user');
            $data['mobile'] = $phone;
            $data['id'] = session('userid');
            $sign = $user->where($data)->find();
            if($sing){
                ajaxReturn(0,'通过','');
            }else{
                ajaxReturn(104,'验证码错误','');
            }
            
        }
    }
    /**
    *用户更换手机发送验证码
    * @version 2015年11月5日15:45:00
    * @author witner
    */
    public function yzm(){
        //调用短信先验证验证码是否正确
        //随机生成验证码
        $ver = rand(100000,999999);
        $phone = I('get.phone');
       // ajaxReturn($phone);
        if(!preg_match("/1[3458]{1}\d{9}$/",$phone)){  
            ajaxReturn(103,'手机输入不符合格式');  
        }
        $sign = $this->sms($phone,$ver);
        if($sign){
            ajaxReturn(101,'发送失败','');
        }else{
            session('yzm',$ver);
            session('phone',$phone);
            ajaxReturn(0,'发送成功','');
        }
    }
    /**
	*我的活动，活动浏览记录以及发起的活动
	* @author ：winter
	* @version ：2015年11月3日16:58:12
	*
    */
    public function myinfo_active(){
    	$acthis = M('user_acthis');
        $active = M('active');
    	$data['userid'] = session('userid');       
    	//用户浏览活动记录，只记录最近的三个，数量由添加记录时控制
    	$acth = $acthis->where($data)->order('instime desc')->select();
        $activeid = '';
        foreach ($acth as $key => $value) {
            $activeid .= $value['activeid'].',';
        }
        $where['id'] = array('in',implode(',',explode(',', $activeid)));
        $where['status'] = 1;
        $this->list = $active->where($where)->select();
    	//用户发起的活动
    	$active = M('active');
    		//待审核的活动
    	$data['status'] = 2;
    	$this->checkpending = $active->where($data)->order('instime desc')->select();
    		//审核通过的活动
    	$data['status'] = 1;
    	$this->passactive = $active->where($data)->order('instime desc')->select();
        $this->mssage();//调用读取未读消息显示
    	$this->display();

    }
    /**
    *我的消息，包括论坛消息和系统消息
    *@author:winter
    *@version:2015年11月3日17:08:03
    */

    public function myinfo_news(){
    //显示status= 1或者2 的系统消息和论坛消息，时间戳要大于此时的，为了定时消息
    	$msg = M('user_msg');
    	$data['status'] = array('neq',0);
    	$data['instime'] = array('elt',time());
    	$data['uid'] = session('userid');
    	//$data['type'] = 1; //系统消息
        //$count      = $msg->where($data)->count();// 查询满足要求的总记录数
        //$Page       = new \Think\Page($count,25);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        //$show       = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        // $this->syslist = $msg->where($data)->order('instime desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        //$this->assign('page',$show);// 赋值分页输出


    	$data['type'] = 2; // 帖子评论回复消息
    	//$this->uselist = $msg->where($data)->order('instime desc')->select();
        $count      = $msg->where($data)->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show       = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $userlist = $msg->where($data)->order('instime desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('pages',$show);// 赋值分页输出
        $user = M('user');
        foreach ($userlist as $key => $value) {
            $uselist = $user->where('id='.$value['uid'])->find();
            $userlist[$key]['headimg'] = $uselist['headpic'];
        }
        $this->userlist = $userlist;
    	$this->display();
    }
    public function myinfo_news_system(){
        $msg = M('user_msg');
        $data['status'] = array('neq',0);
        $data['instime'] = array('elt',time());
        $data['uid'] = session('userid');
        $data['type'] = 1; //系统消息
        $count      = $msg->where($data)->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,25);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show       = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $this->syslist = $msg->where($data)->order('instime desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('page',$show);// 赋值分页输出
        $this->display();
    }
    /**
	*我关注的饭团
	*@author：winter
	*@version:2015年11月3日19:45:10
    */
    public function myinfo_rice(){
    	$userfans  = M('user_fans');
        $fans_club = M('fans_club');
    	$userlist = $userfans->where('userid='.session('userid'))->order('instime desc')->select();
        $fansid = '';
        foreach ($userlist as $key => $value) {
            $fansid .= $value['fansid'].',';
        }
        $fansid = implode(',',explode(',', $fansid));
        $data['id'] = array('in',$fansid);
        $data['status'] = 1;
        $this->clublist = $fans_club->where($data)->select();
    	$this->display();
    }

}