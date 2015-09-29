<?php
// 本类由系统自动生成，仅供测试用途
namespace Stage\Controller;
use Think\Controller;
class ComController extends Controller {

//
    public function vercklogin(){
    	//md5(xxzyw916);
    	$sign = session('uid');
    	if($sign != 'f8e4b89ebe09b7e060d30faf3f0b3047'){
    		  $this->success('请登陆',U('Index/index'),5);
              exit;
    	}

    }
    /*检查数组数据是否为空
    author：winter
    date：2015年9月28日19:41:41
    */
    public function checkDump($data){
    	foreach($data as $key=>$val){
    		if(empty($val)){
    			return 0;
    		}
    	}
    	return 1;
    }
    /**
     * 上传图片--用于异步上传
     * @author huqinlou
     * @version 2015年6月15日 下午5:00:35
     * @Modify witner;2015年9月28日19:43:27
     */
    public function upload_image(){
        $path = I('get.path');
        $path='./Uploads/'.$path.'/'.date('Y-m').'/'.time().rand(10000,99999).'.jpg';

        if(!is_dir(dirname($path))){
            mkdir(dirname($path),0777,true);
        }
        $img = file_get_contents('php://input', 'r');
        file_put_contents($path, $img);
        if(is_file($path)){
            $path=trim($path,'.');
            $this->ajaxReturn(array('status'=>0,'name'=>$path,'url'=>get_attach_path($path)));
        }else{
            $this->ajaxReturn(array('status'=>1,'msg'=>'上传失败'));
        }
    }
}
?>