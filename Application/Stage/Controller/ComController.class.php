<?php
// 本类由系统自动生成，仅供测试用途
namespace Stage\Controller;
use Think\Controller;
class ComController extends Controller {

//
    public function vercklogin(){
    	//md5(xxzyw916);
    	$data['id'] = session('uid');
        $data['name'] = session('name');
        $admin = M('admin');
        $adminval = $admin->where($data)->find();
    	if(!$adminval){
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
    /*判断活动是否有周末
     * @author winter
     * @date 2015年10月10日17:36:27
     * @parameter begin 开始时间
     * @parameter last  结束时间
    */
    public function isWeek($begin,$last){
        $span = intval($last-$begin);

        if($span >= 604800){
            return 1;
        }else if($span > 0){
            $lWeek = date('w',$last);
            $bWeek = date('w',$begin);
            if($lWeek == 6 || $lWeek == 0 || $bWeek == 6 || $bWeek == 0){
                return 1;
            }else{
                if($bWeek > $lWeek){
                    return 1;
                }else{
                    return 0;
                }
            }
        }
    }
}
?>