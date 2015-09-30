<?php
namespace Stage\Controller;
use Think\Controller;
//
class BannerController extends ComController {
    //banner设置
    public function index(){
        $this->vercklogin();
        $this->assign('cur',4);
       // $image = new \Think\Image(); $image->open('./1.jpg');
        // 按照原图的比例生成一个最大为150*150的缩略图并保存为thumb.jpg   1200  470      
       // $image->thumb(120, 47,\Think\Image::IMAGE_THUMB_SCALE)->save('./thumb.jpg');

        //新闻banner读取
        $news = M('news');
        $newsval = $news->select();
        $this->assign('newsval',$newsval);
        //banner 读取
        $banner = M('banner');
        $bannerval = $banner->where(' type= 1')->select();
        $this->assign('bannerval',$bannerval);
       
        $this->display();
    }
    //修改新闻banner
    public function savenewsbanner(){
    	$submit = I('post.submit');
    	$model = M();
    	$banner = M('banner');
    	$model->startTrans();
    	$Duck = true;
    	$banner->where('type = 1')->delete();
    	if(!empty($submit)){
    		for($i = 1;$i<6;$i++){
    			$data['title'] = I('post.name'.$i);
    			$data['img'] = I('post.photo'.$i);
    			$data['newsid']  = I('post.newsid'.$i);
    			

    			if(!empty($data['title']) && !empty($data['img'])){
    				$image = new \Think\Image(); 

	    			$image->open('./Uploads'.$data['img']);
	    			$able = explode('.',$data['img']);
	        		//按照原图的比例生成一个最大为150*150的缩略图并保存为thumb.jpg   1200  470   
	        		$urlimg = './Uploads/small/'.date('YmdHis').rand(1000,99999).'.'.$able[1];  
	       			$image->thumb(120, 47)->save($urlimg);
	       			//,\Think\Image::IMAGE_THUMB_SCALE   /small/banner/2015-09/144361715165112.jpg
	       			$data['smallimg'] = $urlimg;
    				$data['type'] = 1;
    				$sign = $banner->add($data);
    				if(!$sign){
    					$Duck = false;
    				}
    			}
    		}
	    	if($Duck){
	    		$model->commit();
	    		$this->success('保存成功',U('Banner/index'));
	    	}else{
	    		$model->rollback();
	    		$this->success('未做任何保存');
	    	}
    	}
    }
}