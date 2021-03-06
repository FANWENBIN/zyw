<?php
namespace Stage\Controller;
use Think\Controller;
//演艺库类
class PerformingController extends ComController {
    //首页显示
    public function index(){
        $actors = M('actors');

    	//分页
		$count = $actors->order('votes desc')->where('status=2')->count();// 查询满足要求的总记录数
		$Page  = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
		$show  = $Page->show();// 分页显示输出// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$actorsval  = $actors->order('votes desc')->where('status=2')
				->limit($Page->firstRow.','.$Page->listRows)->select();

		$this->assign('actors',$actorsval);// 赋值数据集
		$this->assign('page',$show);// 赋值分页输出

        $this->assign('cur',10);
        $this->display();
        //echo md5('xxxzyw916');        
    }
 
    /*新增演员
    autor：winter
    date：2015年9月23日15:58:17
    */
    public function add(){
        $submit = I('post.submit');
        if(empty($submit)){
            $this->assign('cur',10);
            $this->display();
        }else{
            $actors = M('actors');
            $data['name']    = I('post.name');
            $data['sex']     = I('post.sex');
            $a = $this->checkDump($data);
            $data['birthday']  = I('post.birthday');
                                //strtotime(I('post.timet'))
            $data['national']  = I('post.national');
            $data['address']   = I('post.address');
            $data['nation']    = I('post.nation');
            $data['alias']     = I('post.alias');
            $data['achievement'] = I('post.achievement');
            $data['constellation'] = I('post.constellation');
            $data['blood']     = I('post.blood');
            $data['height']    = I('post.height');
            $data['weight']    = I('post.weight');
            $data['talent']    = I('post.talent');
            $data['about']     = I('post.about');
            $data['promotion'] = 0;
            $data['groupid']   = 0;
            $data['newser']    = I('post.newser');
            $data['area']      = I('post.area');
            if(!$a){
                $this->error('添加失败，主体数据不可为空！',U('Performing/add'));
            }
            $counts = $actors->count();
            $data['rank']    = $counts+1;   //名次
            $data['oldrank'] = $data['rank'];   
            $data['headimg'] = I("post.photo1");
            $data['img']     = I("post.photo2");
            $sur = mb_substr($data['name'],0,1,'utf-8');
            $data['opid']    = md5(date('YmdHis',time()));
            $data['instime'] = time();
            $t_hz = M('t_hz');
            $thzval = $t_hz->where("chinese='".$sur."'")->find();
            $data['chinese_sum'] = $thzval['sum'];
            $data['initial']     = getFirstCharter($data['name']);
            $data['status'] = 2;

            $model = M();                     //开启事物
            $model->startTrans();
            $Duck = true;
            
            $title = I('post.title');
            $img   = I('post.photo');
        
            $production   = M('actors_production');
            $sign = $actors->add($data);

            if($sign === false){
                    $Duck = false;
            }else{
                $id = $sign;
            }
            foreach($title as $key=>$val){
                $c['title'] = $val;
                $c['img']   = $img[$key];
                $c['actorsid'] = $id;
                $sign = $production->add($c);
                if(!$sign){
                    $Duck = false;
                    //echo $production->getlastsql();die();
                }
            }
            if($Duck){
                $model->commit();
                $this->addadminlog($data['name'],$actors->getlastsql(),'新增演员',$id,'gactorid');
                $this->success('新增成功',U('Performing/index'));
            }else{
                $model->rollback();
                $this->error('新增失败');
            }
            
        }
    }


    /*修改演员
    autor：winter
    date：2015年9月23日15:58:17
    */
     public function edit(){
        //
        $submit = I('post.submit');
        $actors = M('actors');
        if(empty($submit)){
            $id = I('get.id');
            $actorsval = $actors->where('id='.$id)->find();
            $this->assign('actors',$actorsval);

            $production = M('actors_production')->where('actorsid='.$id)->select();
            $this->assign('production',$production);

            $this->assign('cur',10);
            $this->display();
        }else{
            $data['name']      = I('post.name');
            $data['promotion'] = I('post.promotion');
            $data['sex']       = I('post.sex');
            $data['groupid']   = I('post.group');

            $id = I('post.actorid');
            $this->checkDump($data);
            $data['birthday']  = strtotime(I('post.birthday'));
                                //strtotime(I('post.timet'))
            $data['achievement'] = I('post.achievement');
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
            $data['newser']    = I('post.newser');
            $data['area']      = I('post.area');
            $model = M();                     //开启事物
            $model->startTrans();
            $Duck = true;

            $title = I('post.title');
            $img   = I('post.photo');
        
            $production   = M('actors_production');
            $production->where('actorsid='.$id)->delete();
            foreach($title as $key=>$val){
                $c['title'] = $val;
                $c['img']   = $img[$key];
                $c['actorsid'] = $id;
                $sign = $production->add($c);
                if(!$sign){
                    $Duck = false;
                }
            }



            $data['headimg'] = I('post.photo1');
            $data['img']     = I('post.photo2');
            $sign = $actors->where('id='.$id)->save($data);
             if($sign === false){
                    $Duck = false;
            }
            if($Duck){
                $model->commit();
                $this->addadminlog($data['name'],$actors->getlastsql(),'修改演员',$id,'gactorid');
                $this->success('修改成功',U('Performing/index'));
            }else{
                $model->rollback();
                $this->error('没做任何修改');
            }
           // }

        }
        
    }

    
    //删除演员
    public function delete(){
        $id     = I('get.id');
        $actors = M('actors');
        $data['status'] = 0;
        $list = $actors->where('id='.$id)->find();
        $sign = $actors->where('id='.$id)->save($data);
        if($sign){
            $this->addadminlog($list['name'],$actors->getlastsql(),'删除演员',$id,'gactorid');
            $this->success('删除成功',U('Performing/index'));
        }else{
            $this->error('未删除');
        }
    }
    //待审核列表
    public function audit(){
        $actors = M('actors');

        //分页
        $count = $actors->where('status = 3')->count();// 查询满足要求的总记录数
        $Page  = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show  = $Page->show();// 分页显示输出// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $actorsval  = $actors->order('instime desc')->where('status=3')
                ->limit($Page->firstRow.','.$Page->listRows)->select();

        $this->assign('actors',$actorsval);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出

        $this->assign('cur',10);
        $this->display();
        //echo md5('xxxzyw916');
    }
    //审核演员详情页
    public function editaudit(){
          //
        $submit = I('post.submit');
        $actors = M('actors');
        if(empty($submit)){
            $id = I('get.id');
            $actorsval = $actors->where('id='.$id)->find();
            $this->assign('actors',$actorsval);

            $production = M('actors_production')->where('actorsid='.$id)->select();
            $this->assign('production',$production);

            $this->assign('cur',10);
            $this->display();
        }else{
            $data['name']      = I('post.name');
            $data['promotion'] = I('post.promotion');
            $data['sex']       = I('post.sex');
            $data['groupid']   = I('post.group');

            $id = I('post.actorid');
            $this->checkDump($data);
            $data['birthday']  = strtotime(I('post.birthday'));
                                //strtotime(I('post.timet'))
            $data['newser']    = I('post.newser');
            $data['area']      = I('post.area');
            $data['achievement'] = I('post.achievement');
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
            $data['status']    = I('post.status');
            $data['remark']    = I('post.remark');
            $model = M();                     //开启事物
            $model->startTrans();
            $Duck = true;

            $title = I('post.title');
            $img   = I('post.photo');
        
            $production   = M('actors_production');
            $production->where('actorsid='.$id)->delete();
            foreach($title as $key=>$val){
                $c['title'] = $val;
                $c['img']   = $img[$key];
                $c['actorsid'] = $id;
                $sign = $production->add($c);
                if(!$sign){
                    $Duck = false;
                }
            }



            $data['headimg'] = I('post.photo1');
            $data['img']     = I('post.photo2');
            $sign = $actors->where('id='.$id)->save($data);
             if($sign === false){
                    $Duck = false;
            }
            if($Duck){
                $model->commit();
                if($data['status'] == 0){
 $this->addadminlog($data['name'],$actors->getlastsql(),'审核演员: 不通过',$id,'gactorid');
                }else{
 $this->addadminlog($data['name'],$actors->getlastsql(),'审核演员: 通过',$id,'gactorid');
                }
           
                $this->success('修改完毕',U('Performing/index'));
            }else{
                $model->rollback();
                $this->error('没做任何修改');
            }
           // }

        }
    }

}