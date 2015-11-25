<?php
// 本类由系统自动生成，仅供测试用途
namespace Home\Controller;
use Think\Controller;
class StageController extends ComController {
    public function index(){
    	$actors = M('actors');
    	$where['newser'] = array('eq',1);
    	$where['status'] = array('in','1,2');
    	$list = $actors->where($where)->order('clickrate desc')->limit(0,18)->select();
    	$this->assign('list',$list);
        //规则
        $configure = M('configure');
        $rule = $configure->where('type = 1')->find();
        $this->assign('rule',$rule);
    	//Banner
    	$banner = M('banner');
    	$bannerval = $banner->where('type = 5')->select();
    	$this->assign('bannerval',$bannerval);
        //作品展示
        $stage = M('stageworks');
        $works = $stage->where('status = 1')->order('instime desc')->select();
        $this->assign('works',$works);
        $this->assign('sign',10);
		$this->display();
    }
    //学员报名
   	public function enroll(){
        $submit = I('post.submit');
        if(empty($submit)){
            $this->display();
        }else{
            $data['title'] = I('post.title'); //标题
            $data['href']  = I('post.href');//视频连接
            $data['content'] = I('post.content');//作品简介
            $data['acsex'] = I('post.acsex');
            $data['status'] = 2;//待审核
                
            $data['instime'] = time();
            
            $data['acname'] = I('post.acname'); //姓名
            $data['accity'] = I('post.accity');//城市
            //$data['accityid'] = I('post.accityid');
            //$data['acprovince'] = I('post.acprovince');//省市
            //$data['acprovinceid'] = I();
            $data['acbirthday'] = I('post.acbirthday');//出生日期
            $data['acheight'] = I('post.acheight');//身高
            $data['acweight'] = I('post.acweight'); //体重
            $data['acschool'] = I('post.acschool');//毕业院校
            $data['phone'] = I('post.phone');//联系手机号

            $upload = new \Think\Upload();// 实例化上传类    
            $upload->maxSize   =     3145728 ;// 设置附件上传大小    
            $upload->exts      =     array('jpg', 'png', 'jpeg');
            // 设置附件上传类型    
            $upload->savePath  =      '/stage/'; // 设置附件上传目录    // 上传文件     
            $info   =   $upload->upload();    
            if(!$info) {// 上传错误提示错误信息        
                $upload->getError();    
            }else{
                $data['img'] = $info['img']['savepath'].$info['img']['savename'];//封面图
                $data['acphoto']  = $info['acphoto']['savepath'].$info['acphoto']['savename'];//照片
                $data['acthrough'] = $info['acthrough']['savepath'].$info['acthrough']['savename']; //演艺经历
            }
            $isempty = $this->checkDump($data);
            
            if(!$isempty){
                $this->jump(U('Stage/enroll'),'对不起，请不要有空数据',3);
                //ajaxReturn(102,'对不起，请不要有空数据','');
            }
            $data['userid'] = session('userid');
            $stage = M('stageworks');
            $sign = $stage->add($data);
            if($sign){
                $this->jump(U('Stage/index'),'恭喜您，提交成功啦~，请等待审核',3);
                //ajaxReturn(0,'成功','');
            }else{
                $this->jump(U('Stage/enroll'),'不好意思，提交失败了~，您可以重新提交',3);
                //ajaxReturn(101,'失败','');
            }
            
        }
        
     
   	}
    
   //做品榜
    public function stageworks(){
        $condition = trim(I('get.condition'));
        if($condition != 'hot' && $condition != 'instime'){
            ajaxReturn(104,'参数错误','');
        }
        $stageworks = M('stageworks');

        //$list = $stageworks->field('id,title,instime,img')->where('status = 1')->order($condition.' desc')->select();

        $count      = $stageworks->where('status = 1')->count();
        // 查询满足要求的总记录数
        $Page       = new \Think\Page($count,6);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show       = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $stageworks->field('id,title,instime,img')->where('status = 1')->order($condition.' desc')->limit($Page->firstRow.','.$Page->listRows)->select();

        if($list === false){
            ajaxReturn(101,'请求失败','');
        }else{
            if(!$list){
                $list = array();
            }
            foreach ($list as $key => $value) {
                $list[$key]['instime'] = date('m-d',$value['instime']);
                $list[$key]['title'] = mb_strlen($value['title'],'utf8')>12?mb_substr($value['title'], 0,12,'utf8').'...':$value['title'];
                $list[$key]['img'] = './Uploads'.$value['img'];
            }
            $data['page'] = ceil($count/6);
            $data['data'] = $list;
            ajaxReturn(0,'',$data);
        }

    }
}