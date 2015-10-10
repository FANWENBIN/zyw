<?php
namespace Stage\Controller;
use Think\Controller;
//活动类
class ActiveController extends ComController {
    //首页显示
    public function index(){
		$this->vercklogin();

		$data['status'] = 1;
		$active = M('active');
		//分页显示
		
		$count      = $active->where($data)->count();// 查询满足要求的总记录数
		$Page       = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
		$show       = $Page->show();// 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$result = $active->where($data)->limit($Page->firstRow.','.$Page->listRows)->order('begin_time desc')->select();
		$this->assign('result',$result);// 赋值数据集
		$this->assign('page',$show);// 赋值分页输出


		$this->assign('cur',6);
        $this->display();
    }
    public function delactive(){
    	$id = I('get.id');
    	$active = M('active');
    	$sign = $active->where('id='.$id)->delete();
    	if($sign){
    		$this->success('删除成功',U('Active/index'));
    	}else{
    		$this->error('未删除任何数据');
    	}
    }
	
}