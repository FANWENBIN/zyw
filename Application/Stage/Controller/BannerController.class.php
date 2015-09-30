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

        $this->display();
    }
}