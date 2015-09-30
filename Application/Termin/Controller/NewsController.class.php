<?php
namespace Termin\Controller;
use Think\Controller;
//微信端新闻类
class NewsController extends ComController {
    /*新闻首页
    author:winter
    date：2015年9月28日14:09:19
    */
    public function index (){
        $news = M('news');
        //首页新闻资讯
        $newsval    = $news->where('`status` = 1 and `order` != 2')->order('`order` desc')->select();
       
        //首页置顶新闻
        $topnews = $news->where('`order` = 2')->find();
      
        $this->assign('news',$newsval);
        
        $this->assign('topnews',$topnews);
        $this->display();
        
    }
   /*粉丝焦点
   author：winter
   date：2015年9月28日14:10:01
   */
    public function infofans(){
    	$this->display();
    }
    /*星闻动向
	author:winter
	date：2015年9月28日14:11:09
    */
    public function infostar(){
    	$this->display();
    }
    /*艺术中国梦
	author:winter
	date:2015年9月28日14:12:31
    */
    public function infodream(){
    	$this->display();
    }
    /*新闻详情
	author:winter
	date:2015年9月28日14:18:16
    */
    public function newsdetail(){
        $id   = I('get.newid','','trim');
        $news = M('news');
        $newsinfo = $news->where('id='.$id)->find();
        $newsinfo['content'] =html_entity_decode($newsinfo['content']);
        $this->assign('newsval',$newsinfo);
    	$this->display();
    }

}