<?php
namespace Stage\Controller;
use Think\Controller;
//好演员类
class RecommendController extends ComController {
    //首页显示
    public function index(){
        $this->vercklogin();
       

    //评委分页
        $recommend = M('recommend');
        $recount = $recommend->count();// 查询满足要求的总记录数
        $rePage = new \Think\Page($recount,10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $reshow = $rePage->show();// 分页显示输出// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $recommendval  = $recommend
                ->limit($rePage->firstRow.','.$rePage->listRows)->select();

        $this->assign('recommend',$recommendval);// 赋值数据集
        $this->assign('repage',$reshow);// 赋值分页输出

        $this->assign('cur',3);
        $this->display();
    
    }
    
    /*新增评委
    autor：winter
    date：2015年9月23日15:58:17
    */
    public function addcommend(){
        $submit = I('post.submit');
        if(empty($submit)){
            $this->display();
        }else{
            $data['type'] = I('post.type');
            $data['name'] = I('post.name');
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
                    $this->success('添加成功',U('Recommend/index'));
                }else{
                    $this->error('添加失败');
                }
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
            $production = M('recommend_production')->where('recommendid='.$id)->select();
            $this->assign('commendval',$commendval);
            $this->assign('production',$production);
            //var_dump($commendval);
            $this->display();
        }else{
            $production   = M('recommend_production');
            $data['name'] = I('post.name');
            $data['type'] = I('post.type');
            $data['nationality'] = I('post.nationality');
            $data['national']    = I('post.national');
            $data['height']      = I('post.height');
            $data['weight']      = I('post.weight');
            $data['address']     = I('post.address');
            $data['birthday']    = I('post.birthday');
            $data['job']         = I('post.job');
            $data['school']      = I('post.job');
            $data['achievement'] = I('post.achievement');
            $data['img']         = I('post.photo1');
            $title = I('post.title');
            $img   = I('post.photo');
            
            $id = I('post.recommendid');
            $model = M();                     //开启事物
            $model->startTrans();
            $Duck = true;
           
            
            $production->where('recommendid='.$id)->delete();
            foreach($title as $key=>$val){
                $c['title'] = $val;
                $c['img']   = $img[$key];
                $c['recommendid'] = $id;
                $sign = $production->add($c);
                if(!$sign){
                    $Duck = false;
                }
            }
           

            $sign = $commend->where('id='.$id)->save($data);
            if($sign === false){
                    $Duck = false;
            }

            if($Duck){
                $model->commit();
                $this->success('修改成功',U('Recommend/index'));
            }else{
                $model->rollback();
                $this->error('没做任何修改');
            }

        }
        
    }
    //删除评委
    public function delcommend(){
        $id = I('get.id');
        $recommend = M('recommend');
        $sign = $recommend->delete($id);
        if($sign){
            $this->success('删除成功',U('Recommend/index'));
        }else{
            $this->error('未删除');
        }
    }


}