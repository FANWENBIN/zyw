<?php
namespace Home\Controller;
use Think\Controller;
//首页类
class IndexController extends ComController {
    public function index(){

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
        $actorsval = $actors->where($where)->order('votes asc')->select();
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
        $actorsval = $actors->where($where)->order('votes asc')->select();
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
        $actorsval = $actors->where($where)->order('votes asc')->select();
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