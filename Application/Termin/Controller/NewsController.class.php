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
    	$this->display();
    }

}