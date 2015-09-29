<?php
namespace Stage\Controller;
use Think\Controller;
//好演员类
class NewsController extends ComController {
    //首页显示
    public function index(){
		$news=  M('news');
		$result=$news->select();
		$this->assign('result',$result);
		$this->assign('cur',5);
        $this->display();   
    }
	public function add(){
		$news=  M('news');
		//var_dump($_POST);
		$_POST['instime']=time();
		//echo $_POST['imgpath'];
		if($_GET['id']>0){
			$id=$_GET['id'];
			$data=$news->where('id ='.$id)->find();
			$this->assign('data',$data);
		}
		if(isset($_POST['submit'])){
			if(!isset($_POST['file2'])||$_POST['file2']!==$data['imgpath']){
			//图片上传
			$upload = new \Think\Upload();// 实例化上传类   
			$upload->maxSize   =     3145728 ;// 设置附件上传大小   
			$upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型  
			$upload->savePath  =      '/news/'; // 设置附件上传目录    
			// 上传文件   
			$info   =   $upload->upload();    
				if(!$info) {// 上传错误提示错误信息      
					$this->error($upload->getError()); 
				}else{
					$_POST['imgpath']=$info['file']['savepath'].$info['file']['savename'];
						if($news->create()){
							if($_POST['hid_id']>0){
								$id=intval($_POST['hid_id']);
								$result=$news->where('id ='.$id)->save();
							}
							else{
								$result=$news->add();
							}
							
							if($result){
								//$this->success('操作成功',U('news/index'),3);
							}
							else{
								//$this->error('数据格式错误',U('news/add'),3);
							}
						}
					}
			}else{
				if($news->create()){
					$result=$news->where('id ='.$id)->save();
					if($result){
						//$this->success('操作成功',U('news/index'),3);
					}
					else{
						//$this->error('数据格式错误',U('news/add'),3);
					}
				}
			}
		}
        $this->display();   
    }  
}