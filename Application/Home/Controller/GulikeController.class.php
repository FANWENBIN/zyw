<?php
// 本类由系统自动生成，仅供测试用途
namespace Home\Controller;
use Think\Controller;
class GulikeController extends ComController {
    public function like(){
        $vedio  = session('video');
        $actors = session('actors');
        $active = session('active');
        $news   = session('news');
        $sqlvedio  = M('vedio');
        $sqlactors = M('actors');
        $sqlactive = M('active');
        $sqlnews   = M('news');
        //喜欢视频
        if(!empty($vedio)){
            $vewhere['status'] = 1;
            $vewhere['type'] = $vedio['type'];
            $veval = $sqlvedio->where($vewhere)->order('hot desc,instime desc,recommend desc')->limit(0,2)->select();
        }else{
            $vewhere['status'] = 1;
            $veval = $sqlvedio->where($vewhere)->order('hot desc,instime desc,recommend desc')->limit(0,2)->select();
        }
        //喜欢的新闻和新闻
        if(!empty($news)){
            $newhere['status'] = 1;
            $newhere['type']   = $news['type'];
            $acval = $sqlnews->where($newhere)->order('hot desc,instime desc,`order` desc')->limit(0,4)->select();
        }
        //喜欢明星动态
        if(!empty($actors)){
            $acwhere['status'] = 1;
            $acwhere['keywords'] = array('like','%'.$actors['name'].'%');
            $acval = $sqlnews->where($acwhere)->order('hot desc,instime desc,`order` desc')->limit(0,4)->select();
        }else{
            if(!$acval){
                $acwhere['status'] = 1;
                $acval = $sqlnews->where($acwhere)->order('hot desc,instime desc,`order` desc')->limit(0,4)->select();
            }
            
        }
        //喜欢的活动
        if(!empty($active)){
            $actwhere['status'] = 1;
           
            $actval = $sqlnews->where($actwhere)->order('concern desc,instime desc')->limit(0,2)->select();
        }else{
            $actwhere['status'] = 1;
            $actval = $sqlnews->where($actwhere)->order('concern desc,instime desc')->limit(0,2)->select();
        }


    }
  
}