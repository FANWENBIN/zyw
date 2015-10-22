<?php
namespace Home\Controller;
use Think\Controller;
class NewsController extends ComController {
    public function index(){
		$news=  M('news');
		//echo $_SERVER['DOCUMENT_ROOT'];
		//var_dump($_SERVER);
		//形象指数
		$resultX=$news->where('type=1 and status!=0')->limit('0,13')->order(array('order'=>'desc','instime'=>'desc'))->select();
		//var_dump($resultX);
		$this->assign('resultX',$resultX);
		//形象指数end
		
		
		//星闻动向
		$resultD=$news->where('type=2 and status!=0')->limit('0,9')->order(array('order'=>'desc','instime'=>'desc'))->select();
		//echo $news->getLastsql();
		//var_dump($resultD);
		$this->assign('resultD',$resultD);
		//星闻动向end
		
		
		//艺术中国梦
		$resultM=$news->where('type=3 and status!=0')->limit('0,3')->order(array('order'=>'desc','instime'=>'desc'))->select();
		$this->assign('resultM',$resultM);
		//艺术中国梦end

		//banner
		$banner = M('banner');
		$bannerval = $banner->where('type = 1')->select();
		
		//
		foreach ($bannerval as $key => $value) {
			$bannerval[$key]['href'] = 'http://m2.nadoo.cn/p/zyw/index.php?m=Home&c=News&a=news_details&id='.$value['newsid'];
		}
		$this->assign('bannerval',$bannerval);
		
		$this->display('news');
    }
    /*新闻详情页
	
    */
	 public function news_details(){
		 //内容
		 $id=intval($_GET['id']);
		 $news=  M('news');
		 $result=$news->where('id ='.$id)->find();
		 $dump['hot'] = $result['hot']+1;
		 $news->where('id='.$id)->save($dump);
		 $this->assign('result',$result);
		 //热点
		 $hotnews=$news->limit('0,5')->order(array('order'=>'desc','instime'=>'desc'))->select();
		 $this->assign('hotnews',$hotnews);
		 //活动
		 $active = M('active');
		 $activeval = $active->where('status = 1')->order('`order` desc,instime desc,concern desc')->find();
		 $this->assign('activeval',$activeval);
		 $this->display('news_details');
	 }
	 //艺术中国梦
	 public function news_dream(){
	 	$news = M('news');
		$count      = $news->where('type=3')->order('instime desc')->count();// 查询满足要求的总记录数
		$Page       = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
		$show       = $Page->show();// 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$list = $news->where('type=3')->order('instime desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign('list',$list);// 赋值数据集
		$this->assign('page',$show);// 赋值分页输出
		//时间顺序
		$timenews = $news->where('type=3')->order('instime desc')->limit(0,7)->select();
		$this->assign('timenews',$timenews);
		//热门新闻
		$hotnews = $news->where('type=3')->order('hot desc')->limit('0,10')->select();
		$this->assign('hotnews',$hotnews);
		//置顶新闻图片
		$topnews = $news->where('type=3 `order` = 2')->order('instime desc')->find();
		$this->assign('topnews',$topnews);
	 	$this->display();
	 }
	 //今日焦点
	 public function news_fans(){
	 	$news = M('news');
	 	//$newsval = $news->where('type=1')->order('instime desc')->select();
		$count      = $news->where('type=1')->order('instime desc')->count();// 查询满足要求的总记录数
		$Page       = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
		$show       = $Page->show();// 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$list = $news->where('type=1')->order('instime desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign('list',$list);// 赋值数据集
		$this->assign('page',$show);// 赋值分页输出
		//时间顺序
		$timenews = $news->where('type=1')->order('instime desc')->limit(0,7)->select();
		$this->assign('timenews',$timenews);
		//热门新闻
		$hotnews = $news->where('type=1')->order('hot desc')->limit('0,10')->select();
		$this->assign('hotnews',$hotnews);
//置顶新闻图片
		$topnews = $news->where('type=3 `order` = 2')->order('instime desc')->find();
		$this->assign('topnews',$topnews);
	 	$this->display();
	 }
	 //星闻动向
	 public function news_star(){
	 	$news = M('news');
		$count      = $news->where('type=2')->order('instime desc')->count();// 查询满足要求的总记录数
		$Page       = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
		$show       = $Page->show();// 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$list = $news->where('type=2')->order('instime desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign('list',$list);// 赋值数据集
		$this->assign('page',$show);// 赋值分页输出
		//时间顺序
		$timenews = $news->where('type=2')->order('instime desc')->limit(0,7)->select();
		$this->assign('timenews',$timenews);
		//热门新闻
		$hotnews = $news->where('type=2')->order('hot desc')->limit('0,10')->select();
		$this->assign('hotnews',$hotnews);
//置顶新闻图片
		$topnews = $news->where('type=3 `order` = 2')->order('instime desc')->find();
		$this->assign('topnews',$topnews);
	 	$this->display();
	 }
}