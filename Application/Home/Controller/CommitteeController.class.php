<?php
// 本类由系统自动生成，仅供测试用途
namespace Home\Controller;
use Think\Controller;
class CommitteeController extends ComController {
	//首页显示
    public function index(){
    	$committee = M('committee');
    	//推荐置顶之声
    	$list = $committee
    			->where('status = 1 and top = 1')
    			->order('top desc,instime desc')
    			->select();
    	$this->assign('list',$list);
        //banner 
        $banner = M('banner');
        $this->bannerlist = $banner->where('type = 7')->find();
        //公告
        $council_rule = M('council_rule');
        $this->tall = $council_rule->where('type = 4 and status = 1')->find();
        //专题摘要
        $this->cel = $council_rule->where('type = 5 and status = 1')->order('instime desc')->limit(0,9)->select();
        //相关简介
        $this->com = $council_rule->where('type = 6 and status = 1')->order('instime desc')->limit(0,2)->select();
        //视频
        $this->video = $council_rule->where('type = 7 and status = 1')->order('instime desc')->limit(0,1)->select();
        //工作介绍
        $this->about = $council_rule->where('type = 8 and status = 1')->order('instime desc')->limit(0,1)->select();
    	//形象监督 
    		//红榜
    	$count = $committee->where('status=1 and type=1')->count();// 查询满足要求的总记录数
    	$Page  = new \Think\Page($count,9);// 实例化分页类 传入总记录数和每页显示的记录数(25)
    	$show  = $Page->show();// 分页显示输出
    	// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
    	$list = $committee->where('status=1 and type=1')->order('instime')->limit($Page->firstRow.','.$Page->listRows)->select();
    	$this->assign('redcom',$list);// 赋值数据集
    	$this->assign('redpage',$show);// 赋值分页输出

    		//黑榜
    	$count = $committee->where('status=1 and type=2')->count();// 查询满足要求的总记录数
    	$Page  = new \Think\Page($count,9);// 实例化分页类 传入总记录数和每页显示的记录数(25)
    	$show  = $Page->show();// 分页显示输出
    	// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
    	$list = $committee->where('status=1 and type=2')->order('instime')->limit($Page->firstRow.','.$Page->listRows)->select();
    	$this->assign('blackcom',$list);// 赋值数据集
    	$this->assign('blackpage',$show);// 赋值分页输出

        $this->assign('sign',4);
		$this->display();
		//echo $ip = get_client_ip();
    }
    //形象监督
    		//红榜
    public function redcom(){
    	$committee = M('committee');
    	$count = $committee->where('status=1')->count();// 查询满足要求的总记录数
    	$Page  = new \Think\Page($count,12);// 实例化分页类 传入总记录数和每页显示的记录数(25)
    	$show  = $Page->show();// 分页显示输出
    	// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
    	$list = $committee->field('id,img,title,instime')->where('status=1')->order('instime')->limit($Page->firstRow.','.$Page->listRows)->select();
    	foreach($list as $key=>$val){
    		$list[$key]['instime'] = date('Y-m-d H:i:s',$val['instime']);
    	}
    	$data['page'] = ceil($count/12);
    	$data['data'] = $list;
    	if($list === false){
			ajaxReturn(101,'请求失败','');
    	}else{
             if(!$data['data']){
                $data['data'] = array();
            }
    		ajaxReturn(0,'',$data);
    	}

    }
    //形象监督
    		//黑榜
    public function blackcom(){
    	$committee = M('committee');
    	$count = $committee->where('status=1 and type=2')->count();// 查询满足要求的总记录数
    	$Page  = new \Think\Page($count,12);// 实例化分页类 传入总记录数和每页显示的记录数(25)
    	$show  = $Page->show();// 分页显示输出
    	// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
    	$list = $committee->field('id,img,title,instime')->where('status=1 and type=2')->order('instime')->limit($Page->firstRow.','.$Page->listRows)->select();
    	foreach($list as $key=>$val){
    		$list[$key]['instime'] = date('Y-m-d H:i:s',$val['instime']);
    	}
    	$data['page'] = ceil($count/12);
    	$data['data'] = $list;
    	if($list === false){
			ajaxReturn(101,'请求失败','');
    	}else{
            if(!$data['data']){
                $data['data'] = array();
            }
    		ajaxReturn(0,'',$data);
    	}
    }
    //演工委之声详情
    public function committee_details(){
        $id = I('get.id');
        $committee = M('committee');
        $result = $committee->where('id='.$id)->find();
        $this->assign('result',$result);

        $news=  M('news');
        //热点
        $hotnews=$news->limit('0,5')->order(array('order'=>'desc','instime'=>'desc'))->select();
        $this->assign('hotnews',$hotnews);
        //活动
        $active = M('active');
        $activeval = $active->where('status = 1')->order('`order` desc,instime desc,concern desc')->find();
        if(!$activeval){
            $this->assign('empty',1);
        }
        $this->assign('activeval',$activeval);
        $this->assign('sign',4);
        $this->display();
    }
    //演工委条例、
    public function committee_rule(){
        $type = I('get.type');
        $council_rule = M('council_rule');
        $this->result = $council_rule->where('type='.$type)->find();

        $news=  M('news');
        //热点
        $hotnews=$news->limit('0,5')->order(array('order'=>'desc','instime'=>'desc'))->select();
        $this->assign('hotnews',$hotnews);
        //活动
        $active = M('active');
        $activeval = $active->where('status = 1')->order('`order` desc,instime desc,concern desc')->find();
if(!$activeval){
            $this->assign('empty',1);
        }
        $this->assign('activeval',$activeval);
        $this->assign('sign',4);
        $this->display();
    }
    
    //演工委摘要
    public function committee_cel(){
        $id = I('get.id');
        $council_rule = M('council_rule');
        $this->result = $council_rule->where('id='.$id)->find();

        $news=  M('news');
        //热点
        $hotnews=$news->limit('0,5')->order(array('order'=>'desc','instime'=>'desc'))->select();
        $this->assign('hotnews',$hotnews);
        //活动
        $active = M('active');
        $activeval = $active->where('status = 1')->order('`order` desc,instime desc,concern desc')->find();
if(!$activeval){
            $this->assign('empty',1);
        }
        $this->assign('activeval',$activeval);
        $this->assign('sign',4);
        $this->display();
    }
    //视频
    public function committee_video(){
        $id = I('get.id');
        $council_rule = M('council_rule');
        $vedioval = $council_rule->where('id='.$id)->find();
        $this->assign('list',$vedioval);
        $this->assign('sign',4);
        $this->display();
    }
}