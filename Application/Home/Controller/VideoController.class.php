<?php
// 
namespace Home\Controller;
use Think\Controller;
class VideoController extends ComController {
  public function index(){
 
  
    //banner
    $banner = M('banner');
    $bannerval = $banner->where('type = 4')->select();
    $this->assign('banner',$bannerval);
    //今日热门
    $vedio = M('vedio');
    $data['status'] = 1;
    $videohot = $vedio->where($data)->order('instime desc,hot desc')->limit(0,14)->select();
    $this->assign('videohot',$videohot);
    //视频汇
    //电影电视
    $data['type'] = 1;
    $tvvideo = $vedio->where($data)->order('instime desc,hot desc')->limit(0,15)->select();
    $this->assign('tvvideo',$tvvideo);
    //制作花絮
    $data['type'] = 2;
    $hxvideo = $vedio->where($data)->order('instime desc,hot desc')->limit(0,15)->select();
    $this->assign('hxvideo',$hxvideo);
    //视频教学
    $data['type'] = 3;
    $spvideo = $vedio->where($data)->order('instime desc,hot desc')->limit(0,15)->select();
    $this->assign('spvideo',$spvideo);
    //探班周低
    $data['type'] = 4;
    $tbvideo = $vedio->where($data)->order('instime desc,hot desc')->limit(0,15)->select();
    $this->assign('tbvideo',$tbvideo);
    //微访谈
    $data['type'] = 7;
    $this->weital = $vedio->where($data)->order('instime desc,hot desc')->limit(0,3)->select();

    
    $this->like();
    $this->assign('sign',9);
    $this->display();
  }
    //视频详情页
  public function video_details(){
    
    $id = I('get.id');
    $vedio = M('vedio');
    $vedioval = $vedio->where('id='.$id)->find();
    session('video',$vedioval);
    $this->assign('list',$vedioval);
    $vedio->where('id='.$id)->setInc('hot',1);
    $this->assign('sign',9);

    $this->display();
  }

   //视频汇
  public function video_more(){
    $this->assign('sign',9);
    $this->display();
  }
  //视频汇调用分页接口数据
  public function vial(){
    $type = intval(trim(I('get.type')));
    $vedio = M('vedio');
    $condition = I('get.condition');
    if($condition == 'hot'){
        $order = 'hot desc,instime desc';
    }else{
        $order = 'instime desc,hot desc';
    }
    //视频汇
    $data['type'] = $type;
    
    $data['status'] = 1;
    //$tvvideo = $vedio->where($data)->order('instime desc,hot desc')->limit(0,15)->select();

    $count      = $vedio->where($data)->count();
    // 查询满足要求的总记录数
    $Page       = new \Think\Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数(25)
    $show       = $Page->show();// 分页显示输出
    // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
    $list = $vedio->field('id,instime,bigimg,title')->where($data)->order($order)->limit($Page->firstRow.','.$Page->listRows)->select();

    $dump['page'] = ceil($count/15);
    $dump['data'] = $list;
    if($list === false){
        ajaxReturn(101,'请求失败','');
    }else{
         if(!$dump['data']){
            $dump['data'] = array();
        }
        foreach($list as $key=>$val){
            $dump['data'][$key]['instime'] = date('m-d',$val['instime']);
            $dump['data'][$key]['title'] = mb_strlen($val['title'],'utf8')>12?mb_substr($val['title'], 0,12,'utf8').'...':$val['title'];
        }
        ajaxReturn(0,'',$dump);
    }
  }
  /**
    *微访谈页面
  */
  public function video_interview(){
    $this->display();
  }

}