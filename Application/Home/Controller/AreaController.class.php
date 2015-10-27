<?php
// 本类供前台条用省市区接口
namespace Home\Controller;
use Think\Controller;
class AreaController extends ComController {

    public function province(){
        $province = M('provinces');
        $pval = $province->field('id,provinceid,province')->select();
        if($pval === false){
            ajaxReturn(101,'请求失败','');
        }else{
            ajaxReturn(0,'',$pval);
        }
    }
    public function city(){
        $id = I('get.provinceid','','intval');
        $cval = M('cities')->field('id,city,provinceid,cityid')->where('provinceid='.$id)->select();
        if($cval === false){
            ajaxReturn(101,'请求失败','');
        }else{
            ajaxReturn(0,'',$cval);
        }
    }

}