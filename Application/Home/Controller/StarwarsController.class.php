<?php
namespace Home\Controller;
use Think\Controller;
//首页类
class StarwarsController extends ComController {
    public function index(){
       //Banner
        $banner = M('banner');
        $bannerval = $banner->where('type = 6')->select();
        $this->assign('bannerval',$bannerval);

        
        $recommend = M('recommend');
        //当代艺术家
        $artists   = $recommend->where('type=1')->select();
        $this->assign('artists',$artists);
        //导演
        $director  = $recommend->where('type=2')->select();
        $this->assign('director',$director);
        //制作人
        $producer  = $recommend->where('type=3')->select();
        $this->assign('producer',$producer);
        //编剧
        $scriptwriter = $recommend->where('type=4')->select();
        $this->assign('scriptwriter',$scriptwriter);

        
        $this->display();
    }
    //推荐团成员详情
    public function starinfo(){
    	$id = I('get.id');
    	$recommend = M('recommend');
    	$recommendval = $recommend->where('id='.$id)->find();
    	$this->assign('list',$recommendval);
    	$recommend_production = M('recommend_production');
    	$production = $recommend_production->where('recommendid='.$id)->select();
    	$this->assign('production',$production);   //代表作
    	$this->assign('default','<li>暂未上传代表作</li>');
    	$this->display();
    }
    //推荐明星接口数据
    public function recommendstar(){
        $actors = M('actors');
        $where['status'] = array(array('neq',0),array('neq',3));
        $where['recommend'] = 1;
        $count      = $actors->where($where)->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,8);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show       = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $actors->field('id,img,name')->where($where)->order('instime desc')->limit($Page->firstRow.','.$Page->listRows)->select();
       
        $data['page'] = ceil($count/8);
        $data['data'] = $list;
        if($list === false){
            ajaxReturn(101,'请求失败','');
        }else{
            ajaxReturn(0,'',$data);
        }


    }
}