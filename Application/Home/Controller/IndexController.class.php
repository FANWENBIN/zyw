<?php
namespace Home\Controller;
use Think\Controller;
//首页类
class IndexController extends ComController {
    public function index(){

		$this->display();
    }

//===========首页分组请求演员数据接口start
    public function redgroup(){
        $sex = I('get.sex');
        $actors = M('actors');
        if(!empty($sex)){
            $where['sex'] = $sex;
        }
        $where['groupid'] = 1;
        $actorsval = $actors->where($where)->order('chinese_sum asc')->select();
        foreach($actorsval as $key=>$val){
            $actorsval[$key]['lifting'] = $val['oldrank']-$val['rank'];
        }
        
        if($actorsval){
            ajaxReturn(0,'',$actorsval);
        }else{
            ajaxReturn(1,'系统错误','');
        }

    }
    public function bluegroup(){
        $sex = I('get.sex');
        $actors = M('actors');
        if(!empty($sex)){
            $where['sex'] = $sex;
        }
        $where['groupid'] = 2;
        $actorsval = $actors->where($where)->order('chinese_sum asc')->select();
        foreach($actorsval as $key=>$val){
            $actorsval[$key]['lifting'] = $val['oldrank']-$val['rank'];
        }
        if($actorsval){
            ajaxReturn(0,'',$actorsval);
        }else{
            ajaxReturn(1,'系统错误','');
        }
    }
    public function greegroup(){
        $sex = I('get.sex');
        $actors = M('actors');
        if(!empty($sex)){
            $where['sex'] = $sex; 
        }
        $where['groupid'] = 3;
        $actorsval = $actors->where($where)->order('chinese_sum asc')->select();
        foreach($actorsval as $key=>$val){
            $actorsval[$key]['lifting'] = $val['oldrank']-$val['rank'];
        }
        if($actorsval){
            ajaxReturn(0,'',$actorsval);
        }else{
            ajaxReturn(1,'系统错误','');
        }
    }
//====================END
}