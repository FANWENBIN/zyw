<?php
// 
namespace Stage\Controller;
use Think\Controller;
class CommitteeController extends ComController {
	//演工委之声红黑榜首页显示
	public function index(){
		$committee  = M('committee');

		$commitval = $committee->where('status = 1')->select();
		$count      = $committee->where('status=1')->count();
		// 查询满足要求的总记录数
		$Page       = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
		$show       = $Page->show();// 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$commitval = $committee->where('status=1')->order('instime desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign('page',$show);// 赋值分页输出

		$this->assign('commitval',$commitval);
		$this->assign('cur',7);
		$this->display();
		//echo $ip = get_client_ip();
	} 
	//新增演工委
	public function add(){
		$submit = I('post.submit');
		if(empty($submit)){
			$this->assign('cur',7);
			$this->display();
		}else{
			$data['title']   = I('post.title');
			$data['type']    = I('post.type');
			$data['img']     = I('post.img');
			$data['digest']  = I('post.digest');
			$data['content'] = I('post.content');
			$array = explode('|', $_POST['instime']);
			$data['instime'] = strtotime($array[0].' '.$array[1]);
			$sign = $this->checkDump($data);
			$sign || $this->error('数据不可为空');
			$data['top']     = I('post.top');
			if($data['top'] == 1){
				$data['topimg'] = I('post.topimg');
			}
			$data['instime'] = time();
			$committee = M('committee');
			$sign = $committee->add($data);
			if($sign){
				$this->success('新增成功',U('Committee/index'));
			}else{
				$this->error('新增失败');
			}
		}
		
	}
	//演工委修改
	public function info(){
		$id = I('get.id');
		$submit = I('post.submit');
		$committee = M('committee');
		if(empty($submit)){
			$commitval = $committee->where('id='.$id)->find();
			$this->assign('list',$commitval);
			$this->assign('cur',7);
			$this->display();
		}else{
			$data['title']   = I('post.title');
			$data['type']    = I('post.type');
			$data['img']     = I('post.img');
			$data['digest']  = I('post.digest');
			$data['content'] = I('post.content');
			$array = explode('|', $_POST['instime']);
			$data['instime'] = strtotime($array[0].' '.$array[1]);
			$sign = $this->checkDump($data);
			$sign || $this->error('数据不可为空');
			$data['top']     = I('post.top');
			if($data['top'] == 1){
				$data['topimg'] = I('post.topimg');
			}else{
				$data['topimg'] = null;
			}
			$id = I('post.id');
			$committee = M('committee');
			$sign = $committee->where('id='.$id)->save($data);
			if($sign){
				$this->success('修改成功',U('Committee/index'));
			}else{
				$this->error('未做新增');
			}

		}
	}
	public function delete(){
		$id = I('get.id');
		$committee = M('committee');
		$data['status'] = 0;
		$sign = $committee->where('id='.$id)->save($data);
		if($sign){
			$this->success('删除成功',U('index'));
		}else{
			$this->error('未删除');
		}

	}
	//委员会简介
	public function about(){
		$council_rule = M('council_rule');
		$submit = I('post.submit');
		if(empty($submit)){
			$data['type'] = 1;
			$data['status'] =1;
			$this->data = $council_rule->where($data)->find();
			$this->assign('cur',7);
			$this->display();
		}else{
			$array = explode('|', $_POST['instime']);
			$_POST['instime'] = strtotime($array[0].' '.$array[1]);
			if($council_rule->create()){
				$id=intval(I('post.id'));
				
				$sign = $council_rule->save();
				if($sign){
					$this->redirect(U('about'),'',0,'');
				}else{
					$this->error('未保存');
				}

			}else{
				$this->error('数据格式错误');
			}
		}
	}
	//委员会章程
	public function rule(){
		$council_rule = M('council_rule');
		$submit = I('post.submit');
		if(empty($submit)){
			$data['type'] = 2;
			$data['status'] =1;
			$this->data = $council_rule->where($data)->find();
			$this->assign('cur',7);
			$this->display();
		}else{
			$array = explode('|', $_POST['instime']);
			$_POST['instime'] = strtotime($array[0].' '.$array[1]);
			if($council_rule->create()){
				$id=intval(I('post.id'));
				
				$sign = $council_rule->save();
				if($sign){
					$this->redirect(U('rule'),'',0,'');
				}else{
					$this->error('未保存');
				}

			}else{
				$this->error('数据格式错误');
			}
		}
	}
	//委员会人员名册
	public function roll(){
		$council_rule = M('council_rule');
		$submit = I('post.submit');
		if(empty($submit)){
			$data['type'] = 3;
			$data['status'] =1;
			$this->data = $council_rule->where($data)->find();
			$this->assign('cur',7);
			$this->display();
		}else{
			$array = explode('|', $_POST['instime']);
			$_POST['instime'] = strtotime($array[0].' '.$array[1]);
			if($council_rule->create()){
				$id=intval(I('post.id'));
				
				$sign = $council_rule->save();
				if($sign){
					$this->redirect(U('roll'),'',0,'');
				}else{
					$this->error('未保存');
				}

			}else{
				$this->error('数据格式错误');
			}
		}
	}
	//公告
	public function notice(){
		$council_rule = M('council_rule');
		$submit = I('post.submit');
		if(empty($submit)){
			$data['type'] = 4;
			$data['status'] =1;
			$this->data = $council_rule->where($data)->find();
			$this->assign('cur',7);
			$this->display();
		}else{
			$array = explode('|', $_POST['instime']);
			$_POST['instime'] = strtotime($array[0].' '.$array[1]);
			if($council_rule->create()){
				$id=intval(I('post.id'));
				
				$sign = $council_rule->save();
				if($sign){
					$this->redirect(U('notice'),'',0,'');
				}else{
					$this->error('未保存');
				}

			}else{
				$this->error('数据格式错误');
			}
		}
	}
	//专题摘要
	public function special(){
		$council_rule = M('council_rule');
		$submit = I('post.submit');
		if(empty($submit)){
			$data['type'] = 5;
			$data['status'] =1;
			//$this->data = $council_rule->where($data)->select();

			$count      = $council_rule->where($data)->count();
			// 查询满足要求的总记录数
			$Page       = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
			$show       = $Page->show();// 分页显示输出
			// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
			$list = $council_rule->where($data)->order('instime desc')->limit($Page->firstRow.','.$Page->listRows)->select();
			$this->assign('data',$list);// 赋值数据集
			$this->assign('page',$show);// 赋值分页输出
			$this->assign('cur',7);
			$this->display();
		}else{
			$array = explode('|', $_POST['instime']);
			$_POST['instime'] = strtotime($array[0].' '.$array[1]);
			if($council_rule->create()){
				$id=intval(I('post.id'));
				$sign = $council_rule->save();
				if($sign){
					$this->redirect(U('notice'),'',0,'');
				}else{
					$this->error('未保存');
				}

			}else{
				$this->error('数据格式错误');
			}
		}
	}
	//专题详情
	public function specialinfo(){
		$council_rule = M('council_rule');
		$id = I('get.id');
		$submit = I('post.submit');
		if(empty($submit)){
			$data['type'] = 5;
			$data['status'] =1;
			$data['id'] = $id;
			$this->data = $council_rule->where($data)->find();

			$this->assign('cur',7);
			$this->display();
		}else{
			$array = explode('|', $_POST['instime']);
			$_POST['instime'] = strtotime($array[0].' '.$array[1]);
			$_POST['type'] = 5;
			if($council_rule->create()){
				$id=intval(I('post.id'));
			
				if($id != 0){
					$sign = $council_rule->save();
				}else{
				
					$sign = $council_rule->add();

				}
				
				if($sign){
					$this->redirect(U('special'),'',0,'');
				}else{
					$this->error('未保存');
				}

			}else{
				$this->error('数据格式错误');
			}
		}
	}
	//专题删除
	public function delspecial(){
		$id = trim(I('get.id'));
		$council_rule = M('council_rule');
		$data['status'] = 0;
		$sign = $council_rule->where('id='.$id)->save($data);
		if($sign){
			$this->redirect(U('special'),'',0,'');
		}else{
			$this->error('删除失败');
		}
	}
	//相关简介
	public function intro(){
		$council_rule = M('council_rule');
		$submit = I('post.submit');
		if(empty($submit)){
			$data['type'] = 6;
			$data['status'] =1;
			//$this->data = $council_rule->where($data)->select();

			$count      = $council_rule->where($data)->count();
			// 查询满足要求的总记录数
			$Page       = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
			$show       = $Page->show();// 分页显示输出
			// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
			$list = $council_rule->where($data)->order('instime desc')->limit($Page->firstRow.','.$Page->listRows)->select();
			$this->assign('data',$list);// 赋值数据集
			$this->assign('page',$show);// 赋值分页输出
			$this->assign('cur',7);
			$this->display();
		}else{
			$array = explode('|', $_POST['instime']);
			$_POST['instime'] = strtotime($array[0].' '.$array[1]);
			if($council_rule->create()){
				$id=intval(I('post.id'));
				$sign = $council_rule->save();
				if($sign){
					$this->redirect(U('notice'),'',0,'');
				}else{
					$this->error('未保存');
				}

			}else{
				$this->error('数据格式错误');
			}
		}
	}


//相关简介详情
	public function introinfo(){
		$council_rule = M('council_rule');
		$id = I('get.id');
		$submit = I('post.submit');
		if(empty($submit)){
			$data['type'] = 6;
			$data['status'] =1;
			$data['id'] = $id;
			$this->data = $council_rule->where($data)->find();

			$this->assign('cur',7);
			$this->display();
		}else{
			$array = explode('|', $_POST['instime']);
			$_POST['instime'] = strtotime($array[0].' '.$array[1]);
			$_POST['type'] = 6;
			if($council_rule->create()){
				$id=intval(I('post.id'));
			
				if($id != 0){
					$sign = $council_rule->save();
				}else{
					
					$sign = $council_rule->add();
				}
				
				if($sign){
					$this->redirect(U('intro'),'',0,'');
				}else{
					$this->error('未保存');
				}

			}else{
				$this->error('数据格式错误');
			}
		}
	}
	//相关简介删除
	public function delintro(){
		$id = trim(I('get.id'));
		$council_rule = M('council_rule');
		$data['status'] = 0;
		$sign = $council_rule->where('id='.$id)->save($data);
		if($sign){
			$this->redirect(U('intro'),'',0,'');
		}else{
			$this->error('删除失败');
		}
	}








	//视频
	public function video(){
		$council_rule = M('council_rule');
		$submit = I('post.submit');
		if(empty($submit)){
			$data['type'] = 7;
			$data['status'] =1;
			//$this->data = $council_rule->where($data)->select();

			$count      = $council_rule->where($data)->count();
			// 查询满足要求的总记录数
			$Page       = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
			$show       = $Page->show();// 分页显示输出
			// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
			$list = $council_rule->where($data)->order('instime desc')->limit($Page->firstRow.','.$Page->listRows)->select();
			$this->assign('data',$list);// 赋值数据集
			$this->assign('page',$show);// 赋值分页输出
			$this->assign('cur',7);
			$this->display();
		}else{
			$array = explode('|', $_POST['instime']);
			$_POST['instime'] = strtotime($array[0].' '.$array[1]);
			if($council_rule->create()){
				$id=intval(I('post.id'));
				$sign = $council_rule->save();
				if($sign){
					$this->redirect(U('notice'),'',0,'');
				}else{
					$this->error('未保存');
				}

			}else{
				$this->error('数据格式错误');
			}
		}
	}
	//视频详情
	public function videoinfo(){
		$council_rule = M('council_rule');
		$id = I('get.id');
		$submit = I('post.submit');
		if(empty($submit)){
			$data['type'] = 7;
			$data['status'] =1;
			$data['id'] = $id;
			$this->data = $council_rule->where($data)->find();

			$this->assign('cur',7);
			$this->display();
		}else{
			$array = explode('|', $_POST['instime']);
			$_POST['instime'] = strtotime($array[0].' '.$array[1]);
			$_POST['type'] = 7;

			if($council_rule->create()){
				$id=intval(I('post.id'));
			
				if($id != 0){
					$sign = $council_rule->save();
				}else{
					$sign = $council_rule->add();
				}
				
				if($sign){
					$this->redirect(U('video'),'',0,'');
				}else{
					$this->error('未保存');
				}

			}else{
				$this->error('数据格式错误');
			}
		}

	}
	//删除视频
	public function delvideo(){
		$id = trim(I('get.id'));
		$council_rule = M('council_rule');
		$data['status'] = 0;
		$sign = $council_rule->where('id='.$id)->save($data);
		if($sign){
			$this->redirect(U('video'),'',0,'');
		}else{
			$this->error('删除失败');
		}
	}
	//工作介绍

	public function introduce(){
		$council_rule = M('council_rule');
		$submit = I('post.submit');
		if(empty($submit)){
			$data['type'] = 8;
			$data['status'] = 1;
			$this->data = $council_rule->where($data)->find();
			$this->assign('cur',7);
			$this->display();
		}else{
			$array = explode('|', $_POST['instime']);
			$_POST['instime'] = strtotime($array[0].' '.$array[1]);
			if($council_rule->create()){
				$id=intval(I('post.id'));
				$sign = $council_rule->save();
				if($sign){
					$this->redirect(U('introduce'),'',0,'');
				}else{
					$this->error('未保存');
				}

			}else{
				$this->error('数据格式错误');
			}
		}
	}

}