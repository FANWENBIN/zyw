<?php
namespace Stage\Controller;
use Think\Controller;
//好演员类
class NewsController extends ComController {
    //首页显示
    public function index(){
		$news=  M('news');
		$map['status'] = 1;
		if(isset($_POST['sousuo'])){
			$keywords=$_POST['keywords'];
			$map['keywords|title|content']=array('like','%'.$keywords.'%');
		} 
		if(isset($_GET['type'])){
			$map['type']=intval($_GET['type']);
		}
		if(!empty($map)){
			session('condition',$map);
		}else{
			session('condition','');
		}
		$resultgroup=$news->field('count(*),type')->where('status = 1')->group('type')->order('type asc')->select();
		foreach ($resultgroup as $key => $value) {
			$result[$value['type']] = $resultgroup[$key];
		}
		
		$this->assign('group',$result);
		//分页显示
		
		$count      = $news->where(session('condition'))->count();// 查询满足要求的总记录数
		$Page       = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
		$show       = $Page->show();// 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$result = $news->where(session('condition'))->limit($Page->firstRow.','.$Page->listRows)->order(array('order'=>'desc','instime'=>'desc'))->select();
		$this->assign('result',$result);// 赋值数据集
		$this->assign('page',$show);// 赋值分页输出
		$this->assign('cur',5);
		//session_destroy();
        $this->display();   
    }
	public function add(){
		ob_end_clean();
		$news=  M('news');
		if($_GET['id']>0){
			$id=$_GET['id'];
			$data=$news->where('id ='.$id)->find();
			$this->assign('cur',5);
			$this->assign('data',$data);
		}
			//$_POST['status'] = 4;
		$array = explode('|', $_POST['instime']);
		$_POST['instime'] = strtotime($array[0].' '.$array[1]);
		
		if(isset($_POST['submit'])){
			//$_POST['instime']=time();
			if($news->create()){

				if($_POST['hid_id']>0){
					$id=intval($_POST['hid_id']);
					$result=$news->where('id ='.$id)->save();	
				}else{
					
					$result=$news->add();	
				}
				if($result){
					if($_POST['hid_id']>0){
						$this->addadminlog($_POST['title'],$news->getlastsql(),'修改新闻',$id,'newsid');
					}else{
						$this->addadminlog($_POST['title'],$news->getlastsql(),'新增新闻',$result,'newsid');
					}
					
					$this->success('操作成功',U('news/index'),3);
				}
				else{
					$this->error('数据格式错误',U('news/add'),3);
				}
			}else{
				$this->error('数据格式错误',U('news/add'),3);
			}
		}else{
			$this->assign('cur',5);
        $this->display();   
        }
    }
	public function delete(){
		$news=  M('news');
		if($_GET['id']>0){
			$id=$_GET['id'];
			$map['status']=0 ;
			$list = $news->where('id='.$id)->find();
			$result=$news->where('id = '.$_GET['id'])->save($map);
			if($result){
				$this->addadminlog($list['title'],$news->getlastsql(),'删除新闻',$id,'newsid');
				$this->success('操作成功',U('news/index'),3);
			}
			else{
				$this->error('数据格式错误',U('news/index'),3);
			}
		}  
    }	
}