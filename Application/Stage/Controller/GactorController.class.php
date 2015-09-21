<?php
namespace Stage\Controller;
use Think\Controller;
//好演员类
class GactorController extends ComController {
    //首页显示
    public function index(){
        $this->vercklogin();
        $actors = M('actors');

    	//分页

		$count = $actors->order('votes desc')->count();// 查询满足要求的总记录数
		$Page  = new \Think\Page($count,1);// 实例化分页类 传入总记录数和每页显示的记录数(25)
		$show  = $Page->show();// 分页显示输出// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$actorsval  = $actors->order('votes desc')
				->limit($Page->firstRow.','.$Page->listRows)->select();

		$this->assign('actors',$actorsval);// 赋值数据集
		$this->assign('page',$show);// 赋值分页输出

    	
        $this->display('gactor');
        //echo md5('xxxzyw916');        
    }

    

}