<?php
namespace Stage\Controller;
use Think\Controller;
//好演员类
class NewsController extends ComController {
    //首页显示
    public function index(){
		$news=  M('news');
		if(isset($_POST['sousuo'])){
			$keywords=$_POST['keywords'];
			$map['keywords|title|content']=array('like','%'.$keywords.'%');
			
			session('condition',$map);   //分页记录条件
			if($keywords){
				session('key',$keywords);   //回显搜索条件
			}else{
				session('key',null);
			}
		}
		

	//	$result=$news->where($map)->select();
		//分页显示
		$count      = $news->where(session('condition'))->count();// 查询满足要求的总记录数
		$Page       = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
		$show       = $Page->show();// 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$result = $news->where(session('condition'))->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign('result',$result);// 赋值数据集
		$this->assign('page',$show);// 赋值分页输出
		$this->assign('cur',5);
        $this->display();   
    }
	public function add(){
		$news=  M('news');
		$_POST['instime']=time();
		if($_GET['id']>0){
			$id=$_GET['id'];
			$data=$news->where('id ='.$id)->find();
			$this->assign('data',$data);
		}
		if(isset($_POST['submit'])){
			if($news->create()){
				if($_POST['hid_id']>0){
					$id=intval($_POST['hid_id']);
					$result=$news->where('id ='.$id)->save();	
				}else{
					$result=$news->add();	
				}
				if($result){
					$this->success('操作成功',U('news/index'),3);
				}
				else{
					$this->error('数据格式错误',U('news/add'),3);
				}
			}else{
				$this->error('数据格式错误',U('news/add'),3);
			}
		}
        $this->display();   
    }  
}