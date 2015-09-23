<?php
namespace Stage\Controller;
use Think\Controller;
//好演员类
class GactorController extends ComController {
    //首页显示
    public function index(){
        $this->vercklogin();
        $actors = M('actors');

    	//好演员分页

		$count = $actors->order('votes desc')->count();// 查询满足要求的总记录数
		$Page  = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
		$show  = $Page->show();// 分页显示输出// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$actorsval  = $actors->order('votes desc')
				->limit($Page->firstRow.','.$Page->listRows)->select();

		$this->assign('actors',$actorsval);// 赋值数据集
		$this->assign('page',$show);// 赋值分页输出

    //评委分页
        $recommend = M('recommend');
        $recount = $recommend->count();// 查询满足要求的总记录数
        $rePage  = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $reshow  = $rePage->show();// 分页显示输出// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $recommendval  = $recommend
                ->limit($Page->firstRow.','.$Page->listRows)->select();

        $this->assign('recommend',$recommendval);// 赋值数据集
        $this->assign('repage',$reshow);// 赋值分页输出


        $this->display('gactor');
        //echo md5('xxxzyw916');        
    }
    /*新增演员
    autor：winter
    date：2015年9月23日15:58:17
    */
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


    /*修改演员
    autor：winter
    date：2015年9月23日15:58:17
    */
    public function upgactor(){
        $submit = I('post.submit');
        $actors = M('actors');
        if(empty($submit)){
            $id = I('get.id');
            $actorsval = $actors->where('id='.$id)->find();
            $this->assign('actors',$actorsval);
            $this->display('upgactor');
        }else{
            $data['name']      = I('post.name');
            $data['promotion'] = I('post.promotion');
            $data['sex']       = I('post.sex');
            $data['groupid']   = I('post.group');
            $id = I('post.actorid');
            $this->checkDump($data);
            $upload = new \Think\Upload();// 实例化上传类   
            $upload->maxSize   =     3145728 ;// 设置附件上传大小   
            $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型  
            $upload->savePath  =      '/stage/'; // 设置附件上传目录    
            // 上传文件   
            $info   =   $upload->upload();    
            if(!$info) {// 上传错误提示错误信息 
                

                $sign = $actors->where('id='.$id)->save($data);
                echo $actors->getlastsql();
                if($sign){
                    $this->success('修改成功',U('Gactor/index'));
                }else{
                    $this->error('没做任何修改');
                }
                

            }else{

                if(isset($info['photo1'])){
                    $data['headimg'] = $info['photo1']['savepath'].$info['photo1']['savename']; 
                }
                if(isset($info['photo2'])){
                    $data['img']     = $info['photo2']['savepath'].$info['photo2']['savename'];
                }

                $sign = $actors->where('id='.$id)->save($data);
                if($sign){
                    $this->success('修改成功',U('Gactor/index'));
                }else{
                    $this->error('没做任何修改');
                }
            }

        }
        
    }

    /*新增评委
    autor：winter
    date：2015年9月23日15:58:17
    */
    public function addcommend(){
        $data['type'] = I('judge');
        $data['name'] = I('name');
        $this->checkDump($data);//检查数据
        $upload = new \Think\Upload();// 实例化上传类   
        $upload->maxSize   =     3145728 ;// 设置附件上传大小   
        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型  
        $upload->savePath  =      '/commend/'; // 设置附件上传目录    
        // 上传文件   
        $info   =   $upload->upload();    
        if(!$info) {// 上传错误提示错误信息      
            $this->error($upload->getError()); 
            exit;  
        }else{// 上传成功  
            $data['img'] = $info['photo']['savepath'].$info['photo']['savename'];
            $commend = M('recommend');
            $sign = $commend->add($data);
            if($sign){
                $this->success('添加成功',U('Gactor/index'));
            }else{
                $this->error('添加失败');
            }
        }
    }
    /*修改评委
    author：witner
    date：2015年9月23日17:02:05
    */
    public function upcommend(){
        $submit = I('post.submit');
        $commend = M('recommend');
        if(empty($submit)){
            $id = I('get.id');
            
            $commendval = $commend->where('id='.$id)->find();
            $this->assign('commendval',$commendval);
            $this->display();
        }else{
            $data['name'] = I('post.name');
            $data['type'] = I('post.judge');
            $id     = I('post.recommendid');
            $this->checkDump($data);
            $upload = new \Think\Upload();// 实例化上传类   
            $upload->maxSize   =     3145728 ;// 设置附件上传大小   
            $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型  
            $upload->savePath  =      '/stage/images/'; // 设置附件上传目录    
            // 上传文件   
            $info   =   $upload->upload();    
            if(!$info) {// 上传错误提示错误信息      
               
                $sign = $commend->where('id='.$id)->save($data);
                if($sign){
                    $this->success('修改成功',U('Gactor/index'));
                }else{
                    $this->error('没做任何修改');
                }

            }else{
                $data['img'] =  $info['photo']['savepath'].$info['photo']['savename'];
                $sign = $commend->where('id='.$id)->save($data);
                if($sign){
                    $this->success('修改成功',U('Gactor/index'));
                }else{
                    $this->error('没做任何修改');
                }
            }
        }
        
    }
    //删除评委
    public function delcommend(){
        $id = I('get.id');
        $recommend = M('recommend');
        $sign = $recommend->delete($id);
        if($sign){
            $this->success('删除成功',U('Gactor/index'));
        }else{
            $this->error('未删除');
        }
    }


}