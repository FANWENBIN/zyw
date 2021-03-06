<?php
// 本类由系统自动生成，仅供测试用途
namespace Home\Controller;
use Think\Controller;
class ActiveController extends ComController {
	//首页显示
    public function index(){
        //banner
        $banner = M('banner');
        $bannerval = $banner->where('type = 2')->select();
        $this->assign('banner',$bannerval);
       $this->assign('sign',3);
		$this->display();
		//echo $ip = get_client_ip();
    }
    public function active_details(){
        $id     = I('get.id');
        $active = M('active');
        $img    = M('active_img');
        $list   = $active->where('id='.$id)->find();
        session('active',$list);
        $this->assign('list',$list);
        $data['concern'] = $list['concern']+1;
        $active->where('id='.$id)->save($data);
        $imglist = $img->where('activeid='.$id)->select();
        $this->assign('imglist',$imglist);
        //用户浏览活动记录
        $uid = session('userid');
        if(!empty($uid)){
            $user_acthis = M('user_acthis');
            $sum = $user_acthis->where('uid='.$uid)->count();
            $is = $user_acthis->where('uid='.$uid.' and activeid='.$id)->count();
            if($sum < 3 && !$is){
                $histriy['activeid'] = $id;
                $histriy['uid']      = $uid;
                $histriy['instime']  = time();
                $user_acthis->add($histriy);
            }else{
                $oldlist = $user_acthis->order('instime asc')->find();
                $histriy['activeid'] = $id;
                $histriy['uid']      = $uid;
                $histriy['instime']  = time();
                $user_acthis->where($oldlist)->save($data);
            }
        }
        $this->assign('sign',3);
        $this->display();
    }
    //========================前台调用活动查询接口==============Start//
    /*类型分类查询*/
    public function activetype(){
    	$type = I('get.type'); //全部 0，线上 1 ，线下 2， 
    	$time = I('get.time'); //全部 0，今天 1 ， 明天 2，后天 3，周末 4，本周 5 ，本周后 6，未开始 7，已结束 8 。
    	$active = M('active');
    	switch ($type) {
    		case '1':
    			$data['linetype'] = 1;
    			break;
    		case '2':
    			$data['linetype'] = 0;
    			break;
    		case '0':
    			break;
    		default:
   				ajaxReturn('101','参数错误','');
    			break;
    	}
    	switch ($time) {
    		case '1':
    			$data['begin_time'] = array('lt',time());
    			$data['last_time']  = array('gt',time());
    			break;
    		case '2':
    			$data['begin_time'] = array('lt',time()+86400);
    			$data['last_time']  = array('gt',time()+86400);
    			break;
    		case '3':
    			$data['begin_time'] = array('lt',time()+172800);
    			$data['last_time']  = array('gt',time()+172800);
    			break;
    		case '4':
    			$data['week'] = 1;
                //$data['last_time'] = 
    			break;
    		case '5':
    			$data['begin_time'] = array('lt',get_week_time('last'));
    			$data['last_time']  = array('gt',get_week_time());
    			$data['_logic'] = 'OR';
    			break;
    		case '6':
    			$data['begin_time'] = array('gt',get_week_time('last'));
    			break;
    		case '7':
    			$data['begin_time'] = array('gt',time());
    			break;
    		case '8':
    			$data['last_time'] = array('lt',time());
    			break;
    		case '0':
    			break;
    		default:
   				ajaxReturn('101','参数错误','');
    			break;
    	}
        $data['status'] = 1;
    	//$activeval = $active->where($data)->select();
        $count      = $active->where($data)->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,12);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show       = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $active->where($data)->order('instime')->limit($Page->firstRow.','.$Page->listRows)->select();
    //$this->assign('list',$list);// 赋值数据集
   // $this->assign('page',$show);// 赋值分页输出

    	if($list === false){
    		ajaxReturn('102','查询数据有误','');
    	}else{
    		foreach($list as $key=>$val){
    			$list[$key]['begin_time'] = date('m.d',$val['begin_time']);
    			$list[$key]['last_time'] = date('m.d',$val['last_time']);
                $list[$key]['content'] = strlen($val['content'])>80?mb_substr($val['content'],0,80,'utf-8').'......':$val['content'];
    		}
            $dump['page'] = ceil($count/12);
            $dump['data'] = $list;
    		ajaxReturn('0','',$dump);
    	}
    }
    //用户发起活动
    public function useraddactive(){
        $a = $this->checklogin();  //验证登陆
         
        if(!$a){
            ajaxReturn(104,'未登录','');
        }
        $active = M('active');
        // $upload = new \Think\Upload();// 实例化上传类    
        // $upload->maxSize   =     3145728 ;// 设置附件上传大小    
        // $upload->exts      =     array('jpg', 'png', 'jpeg');
        // // 设置附件上传类型    
        // $upload->savePath  =      '/active/'; // 设置附件上传目录    // 上传文件     
        // $info   =   $upload->upload();    
        // if(!$info) {// 上传错误提示错误信息        
        //     $this->error($upload->getError());    
        // }else{
        // $data['img'] = $info['mypic']['savepath'].$info['mypic']['savename'];
        // }

        $data['img'] = substr(stripslashes(I('post.img')), 9);
        $data['title']        = I('post.title');
        $data['content']      = I('post.content');
        $data['phone']        = I('post.phone');
        $data['begin_time']   = strtotime(I('post.begin_time'));
        $data['last_time']    = strtotime(I('post.last_time'));
        $data['instime'] = time();
        $span = $data['last_time']-$data['begin_time'];
        if($span < 0){
            ajaxReturn(103,'活动结束日期不可比开始日期早','');
            //$this->error('活动结束日期不可比开始日期早');
        }
        $data['info'] = I('post.info');
        
        $a = $this->checkDump($data);
        if(!$a){
            ajaxReturn(102,'活动主体信息不可为空','');
          //$this->error('');
        }
        $data['linetype']    = I('post.line_type');
        if($data['linetype'] == 0){
            $data['line_address'] = I('post.line_address');
        }
        $data['week'] = $this->isWeek($data['begin_time'],$data['last_time']);
        $data['sponsor_name'] = I('post.sponsor_name');
        $data['sponsor_phone'] = I('post.sponsor_phone');
        $data['sponsor_address'] = I('post.sponsor_address');
        $data['sponsor_email'] = I('post.sponsor_email');
        $data['order'] = I('post.order');
        $data['status'] = 2;
        $data['mast'] = session('username');
        $data['userid'] = session('userid');
        $sign = $active->add($data);
        if($sign){
            ajaxReturn(0,'活动发起成功,静待审核通过','');
        }else{
            ajaxReturn(101,'发起失败','');
        }
    }
}