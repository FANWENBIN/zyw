<?php
namespace Stage\Controller;
use Think\Controller;
//好演员类
class GactorController extends ComController {
    //首页显示候选演员
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


        $this->assign('cur',2);
        $this->display('index_zyw');
        //echo md5('xxxzyw916');        
    }
    //36强显示
    public function threestrong(){
        $this->vercklogin();
        $actors = M('actors');

        //好演员分页

        $count = $actors->where('promotion=36')->order('votes desc')->count();// 查询满足要求的总记录数
        $Page  = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show  = $Page->show();// 分页显示输出// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $actorsval  = $actors->order('votes desc')->
                where('promotion=36')->limit($Page->firstRow.','.$Page->listRows)->select();

        $this->assign('actors',$actorsval);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出


        $this->assign('cur',2);
        $this->display('');
    }
    //最后获胜者
    public function winer(){
        $this->vercklogin();
        $actors = M('actors');

        //好演员分页

        $count = $actors->where('promotion=6')->order('votes desc')->count();// 查询满足要求的总记录数
        $Page  = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show  = $Page->show();// 分页显示输出// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $actorsval  = $actors->order('votes desc')->where('promotion=6')
                ->limit($Page->firstRow.','.$Page->listRows)->select();

        $this->assign('actors',$actorsval);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出


        $this->assign('cur',2);
        $this->display('');
    }
    /*新增演员
    autor：winter
    date：2015年9月23日15:58:17
    */
    public function addactor(){
        $submit = I('post.submit');
        if(empty($submit)){
            $this->assign('cur',2);
            $this->display();
        }else{
            $data['name']    = I('post.name');
            $actors = M('actors');
            
            $data['sex']     = I('post.sex');
            $a = $this->checkDump($data);
            $data['birthday']  = strtotime(I('post.birthday'));
                                //strtotime(I('post.timet'))
            $data['national']  = I('post.national');
            $data['address']   = I('post.address');
            $data['nation']    = I('post.nation');
            $data['alias']     = I('post.alias');
            $data['constellation'] = I('post.constellation');
            $data['blood']     = I('post.blood');
            $data['height']    = I('post.height');
            $data['weight']    = I('post.weight');
            $data['talent']    = I('post.talent');
            $data['about']     = I('post.about');
            $data['promotion'] = I('post.promotion');
            $data['groupid']   = I('post.groupid');
            if(!$a){
                //$this->error('添加失败，不可有空数据！',U('Gactor/index'));
            }
            $counts = $actors->count();
            $data['rank']    = $counts+1;   //名次
            $data['oldrank'] = $data['rank'];
            // $upload = new \Think\Upload();// 实例化上传类   
            // $upload->maxSize   =     3145728 ;// 设置附件上传大小   
            // $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型  
            // $upload->savePath  =      '/stage/images/'; // 设置附件上传目录    
            // // 上传文件   
            // $info   =   $upload->upload();    
            // if(!$info) {// 上传错误提示错误信息      
            //     $this->error($upload->getError()); 
            //     exit;
            // }else{// 上传成功      
            $data['headimg'] = I("post.photo1");
            $data['img']     = I("post.photo2");
            $sur = mb_substr($data['name'],0,1,'utf-8');
            $data['opid']    = md5(date('YmdHis',time()));
            $data['instime'] = time();
            $t_hz = M('t_hz');
            $thzval = $t_hz->where("chinese='".$sur."'")->find();
            $data['chinese_sum'] = $thzval['sum'];
            
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
        //
        $submit = I('post.submit');
        $actors = M('actors');
        if(empty($submit)){
            $id = I('get.id');
            $actorsval = $actors->where('id='.$id)->find();
            $this->assign('actors',$actorsval);
            $this->assign('cur',2);
            $this->display('upgactor');
        }else{
            $data['name']      = I('post.name');
            $data['promotion'] = I('post.promotion');
            $data['sex']       = I('post.sex');
            $data['groupid']   = I('post.group');

            $id = I('post.actorid');
            $this->checkDump($data);
            $data['birthday']  = strtotime(I('post.birthday'));
                                //strtotime(I('post.timet'))
            $data['national']  = I('post.national');
            $data['address']   = I('post.address');
            $data['nation']    = I('post.nation');
            $data['alias']     = I('post.alias');
            $data['constellation'] = I('post.constellation');
            $data['blood']     = I('post.blood');
            $data['height']    = I('post.height');
            $data['weight']    = I('post.weight');
            $data['talent']    = I('post.talent');
            $data['about']     = I('post.about');
            $data['promotion'] = I('post.promotion');
            $data['groupid']   = I('post.groupid');

            // $upload = new \Think\Upload();// 实例化上传类   
            // $upload->maxSize   =     3145728 ;// 设置附件上传大小   
            // $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型  
            // $upload->savePath  =      '/stage/'; // 设置附件上传目录    
            // // 上传文件   
            // $info   =   $upload->upload();    
            // if(!$info) {// 上传错误提示错误信息 
            //     $sign = $actors->where('id='.$id)->save($data);
               
            //     if($sign){
            //         $this->success('修改成功',U('Gactor/index'));
            //     }else{
            //         $this->error('没做任何修改');
            //     }
                

            // }else{

            //     if(isset($info['photo1'])){
            //         $data['headimg'] = $info['photo1']['savepath'].$info['photo1']['savename']; 
            //     }
            //     if(isset($info['photo2'])){
            //         $data['img']     = $info['photo2']['savepath'].$info['photo2']['savename'];
            //     }
            $data['headimg'] = I('post.photo1');
            $data['img']     = I('post.photo2');
            $sign = $actors->where('id='.$id)->save($data);
            if($sign){
                $this->success('修改成功',U('Gactor/index'));
            }else{
                $this->error('没做任何修改');
            }
           // }

        }
        
    }

    
    //删除演员
    public function delactor(){
        $id     = I('get.id');
        $actors = M('actors');
        $sign   = $actors->delete($id);
        if($sign){
            $this->success('删除成功',U('Gactor/index'));
        }else{
            $this->error('未删除');
        }
    }


}