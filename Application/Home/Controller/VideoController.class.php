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
    $vedio->where()->order()->select();
    
    $this->display();
  }
    //视频详情页
  public function vidioinfo(){

  }
   
}