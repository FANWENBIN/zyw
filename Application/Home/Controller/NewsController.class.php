<?php
namespace Home\Controller;
use Think\Controller;
class NewsController extends ComController {
    public function index(){
		$news=  M('news');
		echo $_SERVER['DOCUMENT_ROOT'];
		var_dump($_SERVER);
		//形象指数
		$resultX=$news->where('type=1 and status=1')->limit('0,13')->order("instime desc")->select();
		//var_dump($resultX);
		$this->assign('resultX',$resultX);
		//形象指数end
		
		
		//星闻动向
		$resultD=$news->where('type=2 and status=1')->limit('0,9')->order("instime desc")->select();
		//echo $news->getLastsql();
		//var_dump($resultD);
		$this->assign('resultD',$resultD);
		//星闻动向end
		
		
		//艺术中国梦
		$resultM=$news->where('type=3 and status=1')->limit('0,3')->order("instime desc")->select();
		$this->assign('resultM',$resultM);
		//艺术中国梦end
		$this->display('news');
    }
}