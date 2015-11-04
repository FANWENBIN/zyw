<?php
namespace Home\Controller;
use Think\Controller;
//首页类
class IndexController extends ComController {
    public function index(){
        //演员显示
        $actors = M('actors');
        //红组
        $where1['groupid'] = 1;
        $where1['status'] = 1;
        $redgroup = $actors->where($where1)->order(array('votes'=>'desc','chinese_sum'=>'asc'))->limit(0,10)->select();
        foreach($redgroup as $key=>$val){
            $redgroup[$key]['lifting'] = $val['oldrank']-$val['rank'];
        }
        $this->assign('redgroup',$redgroup); 
        //红组男演员
        $where1['sex'] = 1;
        $manredgroup   = $actors->where($where1)->order(array('votes'=>'desc','chinese_sum'=>'asc'))->limit(0,10)->select();
        foreach($manredgroup as $key=>$val){
            $manredgroup[$key]['lifting'] = $val['oldrank']-$val['rank'];
        }
        $this->assign('manredgroup',$manredgroup); 
        //红组女演员
        $where1['sex'] = 2;
        $menredgroup   = $actors->where($where1)->order(array('votes'=>'desc','chinese_sum'=>'asc'))->limit(0,10)->select(); 
        foreach($menredgroup as $key=>$val){
            $menredgroup[$key]['lifting'] = $val['oldrank']-$val['rank'];
        }
        $this->assign('menredgroup',$menredgroup); 

        //蓝组
        $where2['groupid'] = 2;
        $where2['status'] = 1;
        $bluegroup = $actors->where($where2)->order(array('votes'=>'desc','chinese_sum'=>'asc'))->limit(0,10)->select();
        foreach($bluegroup as $key=>$val){
            $bluegroup[$key]['lifting'] = $val['oldrank']-$val['rank'];
        }
        $this->assign('bluegroup',$bluegroup); 
        //蓝组男演员
        $where2['sex'] = 1;
        $manbluegroup   = $actors->where($where2)->order(array('votes'=>'desc','chinese_sum'=>'asc'))->limit(0,10)->select();
        foreach($manbluegroup as $key=>$val){
            $manbluegroup[$key]['lifting'] = $val['oldrank']-$val['rank'];
        }
        $this->assign('manbluegroup',$manbluegroup); 
        //蓝组女演员
        $where2['sex'] = 2;
        $menbluegroup   = $actors->where($where2)->order(array('votes'=>'desc','chinese_sum'=>'asc'))->limit(0,10)->select();
        foreach($menbluegroup as $key=>$val){
            $menbluegroup[$key]['lifting'] = $val['oldrank']-$val['rank'];
        }
        $this->assign('menbluegroup',$menbluegroup); 

        //绿组
        $where3['groupid'] = 3;
        $where3['status'] = 1;
        $greengroup = $actors->where($where3)->order(array('votes'=>'desc','chinese_sum'=>'asc'))->limit(0,10)->select();
        foreach($greengroup as $key=>$val){
            $greengroup[$key]['lifting'] = $val['oldrank']-$val['rank'];
        }
        $this->assign('greengroup',$greengroup); 
        //红组男演员
        $where3['sex'] = 1;
        $mangreengroup = $actors->where($where3)->order(array('votes'=>'desc','chinese_sum'=>'asc'))->limit(0,10)->select();
        foreach($mangreengroup as $key=>$val){
            $mangreengroup[$key]['lifting'] = $val['oldrank']-$val['rank'];
        }
        $this->assign('mangreengroup',$mangreengroup); 
        //红组女演员
        $where3['sex'] = 2;
        $mengreengroup = $actors->where($where3)->order(array('votes'=>'desc','chinese_sum'=>'asc'))->limit(0,10)->select();
        foreach($mengreengroup as $key=>$val){
            $mengreengroup[$key]['lifting'] = $val['oldrank']-$val['rank'];
        }
        $this->assign('mengreengroup',$mengreengroup);
        //end
		//新闻焦点
		$news = M('news');
		$map['status'] = 1;
		$newsresult=$news->where($map)->order(array('order'=>'desc','instime'=>'desc'))->limit('0,21')->select();
		$this->assign('newsresult',$newsresult);

        //热门活动
        $active = M('active');
        $activeval = $active->where('status = 1')->order('`order` desc,concern desc,instime desc')->limit(0,3)->select();
        $this->assign('activeval',$activeval);
        //进行中的活动
        $where['begin_time'] = array('lt',time());
        $where['last_time']  = array('gt',time());
        $activestart = $active->where($where)->order('`order` desc,concern desc, instime desc')->limit(0,3)->select();
        $this->assign('activestart',$activestart);
        //未开始的活动
        $where1['begin_time'] = array('gt',time());
        $activebegin = $active->where($where1)->order('`order` desc,concern desc, instime desc')->limit(0,3)->select();
        $this->assign('activebegin',$activebegin);
        //一结束的活动
        $where2['last_time'] = array('lt',time());
        $activelast = $active->where($where2)->order('`order` desc,concern desc, instime desc')->limit(0,3)->select();
        $this->assign('activelast',$activelast);
        
        //星Stage
        $where4['newser'] = array('eq',1);
        $where4['status'] = array('in','1,2');
        $list = $actors->where($where4)->order('clickrate desc')->limit(0,12)->select();
        $this->assign('actorslist',$list);
        //演艺人
        $where5['status'] = 1;
        $star = $actors->where($where5)->order('clickrate desc')->limit(0,9)->select();
        $this->assign('starlist',$star);

        $vedio = M('vedio');

        $data['status'] = 1;
        //视频汇
        //电影电视
        $data['type'] = 1;
        $tvvideo = $vedio->where($data)->order('instime desc,hot desc')->limit(0,18)->select();
        $this->assign('tvvideo',$tvvideo);
        //制作花絮
        $data['type'] = 2;
        $hxvideo = $vedio->where($data)->order('instime desc,hot desc')->limit(0,18)->select();
        $this->assign('hxvideo',$hxvideo);
        //视频教学
        $data['type'] = 3;
        $spvideo = $vedio->where($data)->order('instime desc,hot desc')->limit(0,18)->select();
        $this->assign('spvideo',$spvideo);
        //探班周低
        $data['type'] = 4;
        $tbvideo = $vedio->where($data)->order('instime desc,hot desc')->limit(0,18)->select();

        $this->assign('tbvideo',$tbvideo);

        $this->like();
        $this->assign('sign',1);
        //显示用户信息还是未登录。
        //$this->userinfo();
		$this->display();
}

//===========首页分组请求按照名次演员数据接口start
    public function redgroup(){
        $sex = I('get.sex');
        $actors = M('actors');
        if($sex){
            $where['sex'] = $sex;
        }
        $where['groupid'] = 1;
        $actorsval = $actors->where($where)->order('votes desc')->select();
        foreach($actorsval as $key=>$val){
            $actorsval[$key]['lifting'] = $val['oldrank']-$val['rank'];
        }
       
        if($actorsval === false){
            ajaxReturn(1,'系统错误','');
        }else{
            ajaxReturn(0,'',$actorsval);
        }

    }
    public function bluegroup(){
        $sex = I('get.sex');
        $actors = M('actors');
        if($sex){
            $where['sex'] = $sex;
        }
        $where['groupid'] = 2;
        $actorsval = $actors->where($where)->order('votes desc')->select();
        foreach($actorsval as $key=>$val){
            $actorsval[$key]['lifting'] = $val['oldrank']-$val['rank'];
        }
        
        if($actorsval === false){
            ajaxReturn(1,'系统错误','');
        }else{
            ajaxReturn(0,'',$actorsval);
        }
    }
    public function greegroup(){
        $sex = I('get.sex');
        $actors = M('actors');
        if($sex){
            $where['sex'] = $sex; 
        }
        $where['groupid'] = 3;
        $actorsval = $actors->where($where)->order('votes desc')->select();
        foreach($actorsval as $key=>$val){
            $actorsval[$key]['lifting'] = $val['oldrank']-$val['rank'];
        }
        if($actorsval === false){
            ajaxReturn(1,'系统错误','');
        }else{
            ajaxReturn(0,'',$actorsval);
        }
    }
//====================END
}