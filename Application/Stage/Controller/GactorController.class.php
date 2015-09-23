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
		$Page  = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
		$show  = $Page->show();// 分页显示输出// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$actorsval  = $actors->order('votes desc')
				->limit($Page->firstRow.','.$Page->listRows)->select();

		$this->assign('actors',$actorsval);// 赋值数据集
		$this->assign('page',$show);// 赋值分页输出

    
        $this->display('gactor');
        //echo md5('xxxzyw916');        
    }

    public function addactor(){
        $data['name']    = I('post.name');

        
        $data['sex']     = I('post.sex');
        $data['groupid'] = I('post.group');
       
        $a = $this->checkDump($data);
        if(!$a){
            //$this->error('添加失败，不可有空数据！',U('Gactor/index'));
        }


        $upload = new \Think\Upload();// 实例化上传类   
        $upload->maxSize   =     3145728 ;// 设置附件上传大小   
        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型  
        $upload->savePath  =      '/stage/images/'; // 设置附件上传目录    
        // 上传文件   
        $info   =   $upload->upload();    
        if(!$info) {// 上传错误提示错误信息      
            $this->error($upload->getError()); 
            exit;  
        }else{// 上传成功      
            $data['headimg'] = $info['photo1']['savepath'].$info['photo1']['savename']; 
            $data['img']     = $info['photo2']['savepath'].$info['photo2']['savename'];
            $sur = mb_substr($data['name'],0,1,'utf-8');
            $data['opid']    = md5(date('YmdHis',time()));
            $data['instime'] = time();
            $t_hz = M('t_hz');
            $thzval = $t_hz->where("chinese='".$sur."'")->find();
            $data['chinese_sum'] = $thzval['sum'];
            $actors = M('actors');
            $sign = $actors->add($data);

            if($sign){
                $this->success('添加成功',U('Gactor/index'));
            }else{
                 $this->error('添加失败',U('Gactor/index'));
            }
        }
    }
}