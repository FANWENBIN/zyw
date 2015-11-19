<?php
// 本类由系统自动生成，仅供测试用途
namespace Home\Controller;
use Think\Controller;
class NewstarController extends ComController {
    public function index(){
    	 //Banner
        $banner = M('banner');
        $bannerval = $banner->where('type = 3')->select();
        $this->assign('bannerval',$bannerval);
        //热门课程直播课
        $futurestar = M('futurestar');
        $where['status']  = 1;
        $where['type']    = 1;
        $livevideo = $futurestar->where($where)->order('hot desc')->select();
        $this->assign('livevideo',$livevideo);
        //录播课
        $where['type']    = 2;
        $rcevideo = $futurestar->where($where)->order('hot desc')->select();
        $this->assign('rcevideo',$rcevideo);

        //排行
        $data['status'] = 1;
        $hotvideo = $futurestar->where($data)->order('hot desc')->select();
        $this->assign('hotvideo',$hotvideo);
        //最新课程
        $timevideo = $futurestar->where($data)->order('instime desc')->select();
        $this->assign('timevideo',$timevideo);
        //推荐课程
        $revi = $futurestar->where($data)->order('top desc,hot desc')->select();
        $this->assign('revi',$revi);
        $this->assign('sign',8);
		$this->display();
    }
    //教程详情页面
    public function classinfo(){
        $id = I('get.id');
        $futurestar = M('futurestar');
        $vedioval = $futurestar->where('id='.$id)->find();
        $this->assign('list',$vedioval);
        $futurestar->where('id = '.$id)->setInc('hot',1);
        $this->assign('sign',8);
        $this->display();
    }
   
}