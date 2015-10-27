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
		$this->display();
    }
    //学员报名
   	public function apply(){
        
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