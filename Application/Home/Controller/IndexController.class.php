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
        $redgroup = $actors->where($where1)->order(array('votes'=>'desc','chinese_sum'=>'asc'))->select();
        foreach($redgroup as $key=>$val){
            $redgroup[$key]['lifting'] = $val['oldrank']-$val['rank'];
        }
        $this->assign('redgroup',$redgroup); 
        //红组男演员
        $where1['sex'] = 1;
        $manredgroup   = $actors->where($where1)->order(array('votes'=>'desc','chinese_sum'=>'asc'))->select();
        foreach($manredgroup as $key=>$val){
            $manredgroup[$key]['lifting'] = $val['oldrank']-$val['rank'];
        }
        $this->assign('manredgroup',$manredgroup); 
        //红组女演员
        $where1['sex'] = 2;
        $menredgroup   = $actors->where($where1)->order(array('votes'=>'desc','chinese_sum'=>'asc'))->select();
        foreach($menredgroup as $key=>$val){
            $menredgroup[$key]['lifting'] = $val['oldrank']-$val['rank'];
        }
        $this->assign('menredgroup',$menredgroup); 

        //蓝组
        $where2['groupid'] = 2;
        $bluegroup = $actors->where($where2)->order(array('votes'=>'desc','chinese_sum'=>'asc'))->select();
        foreach($bluegroup as $key=>$val){
            $bluegroup[$key]['lifting'] = $val['oldrank']-$val['rank'];
        }
        $this->assign('bluegroup',$bluegroup); 
        //蓝组男演员
        $where2['sex'] = 1;
        $manbluegroup   = $actors->where($where2)->order(array('votes'=>'desc','chinese_sum'=>'asc'))->select();
        foreach($manbluegroup as $key=>$val){
            $manbluegroup[$key]['lifting'] = $val['oldrank']-$val['rank'];
        }
        $this->assign('manbluegroup',$manbluegroup); 
        //蓝组女演员
        $where2['sex'] = 2;
        $menbluegroup   = $actors->where($where2)->order(array('votes'=>'desc','chinese_sum'=>'asc'))->select();
        foreach($menbluegroup as $key=>$val){
            $menbluegroup[$key]['lifting'] = $val['oldrank']-$val['rank'];
        }
        $this->assign('menbluegroup',$menbluegroup); 

        //绿组
        $where3['groupid'] = 3;
        $greengroup = $actors->where($where3)->order(array('votes'=>'desc','chinese_sum'=>'asc'))->select();
        foreach($greengroup as $key=>$val){
            $greengroup[$key]['lifting'] = $val['oldrank']-$val['rank'];
        }
        $this->assign('greengroup',$greengroup); 
        //红组男演员
        $where3['sex'] = 1;
        $mangreengroup = $actors->where($where3)->order(array('votes'=>'desc','chinese_sum'=>'asc'))->select();
        foreach($mangreengroup as $key=>$val){
            $mangreengroup[$key]['lifting'] = $val['oldrank']-$val['rank'];
        }
        $this->assign('mangreengroup',$mangreengroup); 
        //红组女演员
        $where3['sex'] = 2;
        $mengreengroup = $actors->where($where3)->order(array('votes'=>'desc','chinese_sum'=>'asc'))->select();
        foreach($mengreengroup as $key=>$val){
            $mengreengroup[$key]['lifting'] = $val['oldrank']-$val['rank'];
        }
        $this->assign('mengreengroup',$mengreengroup);
        //end
		//新闻焦点
		$news = M('news');
		$map['status']!=0;
		$newsresult=$news->where($map)->order(array('order'=>'desc','instime'=>'desc'))->limit('0,21')->select();
		$this->assign('newsresult',$newsresult);

        //活动
        $active = M('active');
        $activeval = $active->where('status = 1')->order('`order` desc,instime desc')->limit('0,9')->select();
        $this->assign('activeval',$activeval);
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