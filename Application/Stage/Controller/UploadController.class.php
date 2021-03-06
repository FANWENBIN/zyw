<?php

namespace Stage\Controller;
use Think\Controller;

/**
 * 上传图片
 * @author huqinlou
 * @version 2015年8月19日 下午3:38:21
 */
class UploadController extends Controller{
    
    
    /**
     * 上传图片--用于异步上传
     * @author huqinlou
     * @version 2015年6月15日 下午5:00:35
     * @Modify witner;2015年9月28日19:43:27
     */
    public function upload_image(){
        $para = I('get.path');
        $name = '/'.$para.'/'.date('Y-m').'/'.time().rand(10000,99999).'.jpg';
        $path='./Uploads'.$name;

        if(!is_dir(dirname($path))){
            mkdir(dirname($path),0777,true);
        }

        $img = file_get_contents('php://input', 'r');
        file_put_contents($path, $img);
        if(is_file($path)){
            $path=trim($path,'.');
            $this->ajaxReturn(array('status' => 0,
                                    'msg'    =>'',
                                    'data'   => array('name'=>$name)
                                )
                            );
        }else{
            $this->ajaxReturn(array('status'=>1,'msg'=>'上传失败'));
        }
    }
    public function examimg(){
        $upload = new \Think\Upload();
        $upload->maxSize = 3145728;
        $upload->exts = array('jpg','gif','png','jpeg');
        $upload->savePath = 'examimg/';
        $info = $upload->upload();
        var_dump($info);
        $src = './Uploads/'.$info['img']['savepath'].$info['img']['savename'];
        echo "<img src='".$src."''>";

        var_dump(extension_loaded());

        $image = new \Think\Image();
        $image->open($src);
        $image->thumb(300,200)->save($src);
        echo "<img src='".$src."'' width='453' height='271'>";
    }

}