<?php
// 本类由系统自动生成，仅供测试用途
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

    $this->display();
  }
    //视频详情页
  public function video_details(){
    $id = I('get.id');
    $vedio = M('vedio');
    $vedioval = $vedio->where('id='.$id)->select();
    
    $this->assign('list',$vedioval);
    $this->display();
  }
   //视频汇
  public function video_more(){
    $this->display();
  }
}