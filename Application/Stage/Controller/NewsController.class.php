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
		}
		$result=$news->where($map)->select();
		$this->assign('result',$result);
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