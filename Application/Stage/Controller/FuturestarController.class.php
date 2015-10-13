<?php
//
namespace Stage\Controller;
use Think\Controller;
class FuturestarController extends ComController {
	//明日之星首页显示
	public function index(){
		$committee  = M('futurestar');

	
	

		$count      = $committee->where('status=1')->count();// 查询满足要求的总记录数
		$Page       = new \Think\Page($count,10);
		// 实例化分页类 传入总记录数和每页显示的记录数(25)
		$show       = $Page->show();// 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$list = $committee
				->where('status=1')
				->order('instime')
				->limit($Page->firstRow.','.$Page->listRows)
				->select();

		$this->assign('commitval',$list); // 赋值数据集
		$this->assign('page',$show);// 赋值分页输出

		$this->assign('cur',8);
		$this->display();
		//echo $ip = get_client_ip();
		
	}
	/*设置修改明日之星管理
	
	*/
	public function info(){
		$id = I('get.id');
		$futurestar = M('futurestar');
		$submit = I('post.submit');
		if(empty($submit)){
			$futureval = $futurestar->where('id='.$id)->find();
			$this->assign('list',$futureval);
			$this->assign('cur',8);
			$this->display();
		}else{
			$data['title'] = I('post.title');
			$data['img']   = I('post.img');
			$data['href']  = I('post.href');
			$data['type']  = I('post.type');
			$data['top']   = I('post.top');
			$sign = $this->checkDump($data);
			if(!$sign){
				$this->error('填写数据信息不可为空');
			}

			if($data['top'] == 1){
				$data['topimg'] = I('post.topimg');
			}else{
				$data['topimg'] = null;
			}

		
			$sign = $futurestar->where('id='.$id)->save($data);
			if($sign){
				$this->success('修改成功',U('Futuresatr/index'));
			}else{
				$this->error('未做任何修改');
			}

		}
	}
	/*新增数据*/
	public function add(){
		$futurestar = M('futurestar');
		$submit = I('post.submit');
		if(empty($submit)){
			$this->assign('cur',8);
			$this->display();
		}else{
			$data['title'] = I('post.title');
			$data['img']   = I('post.img');
			$data['href']  = I('post.href');
			$data['type']  = I('post.type');
			$data['top']   = I('post.top');
			$sign = $this->checkDump($data);
			if(!$sign){
				$this->error('填写数据信息不可为空');
			}

			if($data['top'] == 1){
				$data['topimg'] = I('post.topimg');
			}else{
				$data['topimg'] = null;
			}

			$data['instime'] = time();
			$sign = $futurestar->where('id='.$id)->save($data);
			if($sign){
				$this->success('新增成功',U('Futuresatr/index'));
			}else{
				$this->error('新增失败');
			}

		}
	}
	/*删除数据*/
	public function delete(){
		$id = I('get.id');
		$data['status'] = 0;
		$sign = $futurestar->where('id='.$id)->save($data);
		if($sign){
			$this->success('删除成功',U('Futurestar/index'));
		}else{
			$this->error('未删除');
		}

	}
	

}