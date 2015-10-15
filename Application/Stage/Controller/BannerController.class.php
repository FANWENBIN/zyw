<?php
namespace Stage\Controller;
use Think\Controller;
/*各页面banner设置
author:winter
date:2015年10月8日15:06:40
*/
class BannerController extends ComController {
    //==================================新闻banner===//
    public function index(){

        $this->assign('cur',4);
       // $image = new \Think\Image(); $image->open('./1.jpg');
        // 按照原图的比例生成一个最大为150*150的缩略图并保存为thumb.jpg   1200  470      
       // $image->thumb(120, 47,\Think\Image::IMAGE_THUMB_SCALE)->save('./thumb.jpg');

        //新闻banner读取
        $news = M('news');
        $newsval = $news->where('status <> 0')->select();
        $this->assign('a','<option>暂时没有数据</option>');
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
//==================================新闻END===//

    //==================================活动banner===//
    //活动banner显示
    public function active(){
        //活动读取
        $active = M('active');
        $activeval = $active->where('status <> 0')->select();
      
        $this->assign('a','<option>暂时没有数据</option>');
        $this->assign('activeval',$activeval);
        //banner 读取
        $banner = M('banner');
        $bannerval = $banner->where(' type= 2')->select();
        $this->assign('bannerval',$bannerval);
        $this->assign('cur',4);
        $this->display();
    }
    //活动banner修改
    public function saveactivebanner(){
        $submit = I('post.submit');
        $model = M();
        $banner = M('banner');
        $model->startTrans();
        $Duck = true;
        $banner->where('type = 2')->delete();
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
                    $data['type'] = 2;
                    $sign = $banner->add($data);
                    if(!$sign){
                        $Duck = false;
                    }
                }
            }
            if($Duck){
                $model->commit();
                $this->success('保存成功',U('Banner/active'));
            }else{
                $model->rollback();
                $this->success('未做任何保存');
            }
        }
    }
//============活动Banner==END====================//


    //===============明日之星==================================//
    public function futurestar(){
        //明日之星读取
        $active = M('futurestar');
        $activeval = $active->where('status = 1')->select();
        $this->assign('a','<option>暂时没有数据</option>');
        $this->assign('activeval',$activeval);
        //banner 读取
        $banner = M('banner');
        $bannerval = $banner->where(' type= 3')->select();
        $this->assign('bannerval',$bannerval);
        $this->assign('cur',4);
        $this->display();
    }
    public function savefuturestar(){
        //刚才洗完袜子和内裤，去了躺厕所，回来时，
        // 看到舍友正在拿着我的内裤一直嗅！我很害怕，
        // 就在这时，这货来了句：操，你又他妈用我洗衣液。
        $submit = I('post.submit');
        $model = M();
        $banner = M('banner');
        $model->startTrans();
        $Duck = true;
        $banner->where('type = 3')->delete();
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
                    $data['type'] = 3;
                    $sign = $banner->add($data);
                    if(!$sign){
                        $Duck = false;
                    }
                }
            }
            if($Duck){
                $model->commit();
                $this->success('保存成功',U('Banner/futurestar'));
            }else{
                $model->rollback();
                $this->success('未做任何保存');
            }
        }

    }
//===================明日之星END==========================//

    //==============视频banner管理=======================//
    public function vedio(){
        //视频读取
        $active = M('vedio');
        $activeval = $active->where('status = 1')->select();
        $this->assign('a','<option>暂时没有数据</option>');
        $this->assign('activeval',$activeval);
        //banner 读取
        $banner = M('banner');
        $bannerval = $banner->where(' type= 4')->select();
        $this->assign('bannerval',$bannerval);
        $this->assign('cur',4);
        $this->display();
    }
    public function savevedio(){
        //刚才洗完袜子和内裤，去了躺厕所，回来时，
        // 看到舍友正在拿着我的内裤一直嗅！我很害怕，
        // 就在这时，这货来了句：操，你又他妈用我洗衣液。
        $submit = I('post.submit');
        $model = M();
        $banner = M('banner');
        $model->startTrans();
        $Duck = true;
        $banner->where('type = 4')->delete();
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
                    $data['type'] = 4;
                    $sign = $banner->add($data);
                    if(!$sign){
                        $Duck = false;
                    }
                }
            }
            if($Duck){
                $model->commit();
                $this->success('保存成功',U('Banner/vedio'));
            }else{
                $model->rollback();
                $this->success('未做任何保存');
            }
        }

    }
//============================视频Banner管理END=========================//


    //===================Stage banner 管理start====================//
     public function stage(){
        //演员读取
        $active = M('actors');
        $activeval = $active->where('status <> 0')->select();
        $this->assign('a','<option>暂时没有数据</option>');
        $this->assign('newsval',$activeval);
     
        //banner 读取
        $banner = M('banner');
        $bannerval = $banner->where(' type= 5')->select();
        $this->assign('bannerval',$bannerval);
        $this->assign('cur',4);
        $this->display();
    }
    public function savestage(){
        //刚才洗完袜子和内裤，去了躺厕所，回来时，

        // 看到舍友正在拿着我的内裤一直嗅！我很害怕，

        // 就在这时，这货来了句：操，你又他妈用我洗衣液。
        $submit = I('post.submit');
        $model = M();
        $banner = M('banner');
        $model->startTrans();
        $Duck = true;
        $banner->where('type = 5')->delete();
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
                    $image->thumb(120,47)->save($urlimg);
                    //,\Think\Image::IMAGE_THUMB_SCALE   /small/banner/2015-09/144361715165112.jpg
                    $data['smallimg'] = $urlimg;
                    $data['type'] = 5;
                    $sign = $banner->add($data);
                    if(!$sign){
                        $Duck = false;
                    }
                }
            }
            if($Duck){
                $model->commit();
                $this->success('保存成功',U('Banner/stage'));
            }else{
                $model->rollback();
                $this->success('未做任何保存');
            }
        }

    }
}