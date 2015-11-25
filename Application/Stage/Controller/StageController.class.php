<?php
namespace Stage\Controller;
use Think\Controller;
//视频类
class StageController extends ComController {
    //首页显示
    public function index(){
        $stage = M('stageworks');
        $works = $stage->where('status = 1')->order('instime desc')->select();

        $this->assign('list',$works);
        $this->assign('cur',13);
        $this->display();
    }
    //修改视频
    public function edit(){
        $submit = I('post.submit');
        $stage  = M('stageworks');
        if(empty($submit)){
            $id = I('get.id');
            $this->assign('cur',13);
            $vedioval = $stage->where('id='.$id)->find();
            $this->assign('vedioval',$vedioval);
            $this->display();
        }else{
            $id = I('get.id');
            $data['title'] = I('post.title');
            $data['href']  = I('post.href');
            $data['img']   = I('post.img');
            $data['content'] = I('post.content');

            //var_dump($data);die();
            $sign = $this->checkDump($data);
            if(!$sign){
                $this->error('主要信息不可为空');
            }
            $data['hot'] = I('hot');
            $instime = explode('|',I('instime'));

            $data['instime'] = strtotime($instime[0].' '.$instime[1]);
            $data['acname'] = I('acname');
            $data['acsex'] = I('acsex');
            $data['acprovince'] = I('acprovince');
            $data['accity'] = I('accity');
            $data['acbirthday'] = strtotime(I('acbirthday'));
            $data['acheight'] = I('acheight');
            $data['acweight'] = I('acweight');
            $data['acschool'] = I('acschool');
            $data['acphoto'] = I('acphoto');
            $data['acthrough'] = I('acthrough');
            $data['phone'] = I('phone');
            
            $sign = $stage->where('id='.$id)->save($data);
            if($sign){
                $this->addadminlog($data['title'],$stage->getlastsql(),'修改Stage作品',$id,'stageid');
                $this->success('修改成功',U('Stage/index'));
            }else{
                $this->error('未做任何修改');
            }

        }
    }
    //删除作品
    public function delete(){
        $id = I('post.id','','intval');
        $stage = M('stageworks');
        $data['status'] = 0;
        $list = $stage->where('id='.$id)->find();
        $sign = $stage->where('id='.$id)->save($data);
        if($sign){
        $this->addadminlog($list['title'],$stage->getlastsql(),'删除Stage作品',$id,'stageid');
            $this->success('删除成功',U('Vedio/index'));
        }else{
            $this->error('未删除');
        }
    }
    //新增数据
    public function add(){
        $submit = I('post.submit');
        $stage  = M('stageworks');
        if(empty($submit)){

            $this->assign('cur',13);

            $this->display();
        }else{
            $id = I('get.id');
            $data['title'] = I('post.title');
            $data['href']  = I('post.href');
            $data['img']   = I('post.img');
            $data['content'] = I('post.content');
            //var_dump($data);die();
            $data['instime'] = time();
            $sign = $this->checkDump($data);
            if(!$sign){
                $this->error('主要信息不可为空');
            }
            $data['hot'] = I('hot');
            $instime = explode('|',I('instime'));
            
            $data['instime'] = strtotime($instime[0].' '.$instime[1]);
            $data['acname'] = I('acname');
            $data['acsex'] = I('acsex');
            $data['acprovince'] = I('acprovince');
            $data['accity'] = I('accity');
            $data['acbirthday'] = strtotime(I('acbirthday'));
            $data['acheight'] = I('acheight');
            $data['acweight'] = I('acweight');
            $data['acschool'] = I('acschool');
            $data['acphoto'] = I('acphoto');
            $data['acthrough'] = I('acthrough');
            $data['phone'] = I('phone');
            $sign = $stage->add($data);
            if($sign){
              $this->addadminlog($data['title'],$stage->getlastsql(),'新增Stage作品',$id,'stageid');  
                $this->success('新增成功',U('Stage/index'));
            }else{
                $this->error('新增失败');
            }
        }
    }
    //审核作品列表
    public function audit(){
        $stage = M('stageworks');
        $works = $stage->where('status = 2')->order('instime desc')->select();
        $this->assign('list',$works);
        $this->assign('cur',13);
        $this->display();
    }
    //审核作品
    public function editaudit(){
        $submit = I('post.submit');
        $stage  = M('stageworks');
        if(empty($submit)){
            $id = I('get.id');
            $this->assign('cur',13);
            $vedioval = $stage->where('id='.$id)->find();
            $this->assign('vedioval',$vedioval);
            $this->display();
        }else{
            $id = I('get.id');
            $data['title'] = I('post.title');
            $data['href']  = I('post.href');
            $data['img']   = I('post.img');
            $data['content'] = I('post.content');
            $data['remark'] = I('post.remark');
            //var_dump($data);die();
            $sign = $this->checkDump($data);
            if(!$sign){
                $this->error('主要信息不可为空');
            }
            $data['hot'] = I('hot');
            $instime = explode('|',I('instime'));
            
            $data['instime'] = strtotime($instime[0].' '.$instime[1]);
            $data['acname'] = I('acname');
            $data['acsex'] = I('acsex');
            $data['acprovince'] = I('acprovince');
            $data['accity'] = I('accity');
            $data['acbirthday'] = strtotime(I('acbirthday'));
            $data['acheight'] = I('acheight');
            $data['acweight'] = I('acweight');
            $data['acschool'] = I('acschool');
            $data['acphoto'] = I('acphoto');
            $data['acthrough'] = I('acthrough');
            $data['phone'] = I('phone');
            $data['status'] = I('post.status');
            $data['remark'] = I('post.remark');
            $val = $stage->where('id='.$id)->find();
            $sign = $stage->where('id='.$id)->save($data);
            if($sign){
                
                if($data['status'] == 1){
                   // $id,$content,$nickname,$type,$time = ''
                    $content = '您发布的作品通过审核：'.$val['remark'];
                    $tall = '通过';
                }else{
                    $content = '您发布的作品未通过审核：'.$val['remark'];
                    $tall = "不通过";
                } 
                
                $userid = $val['userid'];
                $user = M('user')->where('id='.$userid)->find();
                $nickname = $user['nickname'];
                $time = time();
                //给用户发送消息
                $this->sendmsg($userid,$content,$nickname,1,$time);
                $this->addadminlog($data['title'],$stage->getlastsql(),'审核Stage作品:'.$tall,$id,'stageid');
                $this->success('审核完毕',U('Stage/audit'));
            }else{
                $this->error('未做任何审核');
            }

        }
    }
}