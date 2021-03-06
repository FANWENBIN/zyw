<?php
// 本类由系统自动生成，仅供测试用途
namespace Stage\Controller;
use Think\Controller;
class ComController extends Controller {
    public function __construct(){
        parent::__construct();
        //验证登录
        $this->vercklogin();
        //活动待审核
        $active = M('active');
        $sum = $active->where('status = 2')->count();
        $this->assign('sms',$sum);
        //演员待审核
        $actors = M('actors');
        $actorssum = $actors->where('status = 3')->count();
        $this->assign('actorssum',$actorssum);
        //Stage作品审核
        $stage = M('stageworks');
        $works = $stage->where('status = 2')->count();
        $this->assign('works',$works);
        //饭团待审核
        $fans = M('fans_club');
        $this->fanssum = $fans->where('status = 2')->count();

        $id = session('uid');
        if($id == 1){
            $this->assign('isadmin',1);
        }
        //记录最后一次操作时间作为登出时间
        $this->setoutime();

    }
    /**
    *J记录登出时间
    *@author winter
    *@version 2015年11月23日20:52
    */
    public function setoutime(){
        $online = M('admin_online');
        $data['outime'] = time();
        $online->where('id='.session('outimeid'))->save($data);
    }
//验证登录
    public function vercklogin(){
    	//md5(xxzyw916);
    	$data['id'] = session('uid');
        $data['name'] = session('name');
        $admin = M('admin');
        $adminval = $admin->where($data)->find();
    	if(!$adminval){
    		 // $this->success('请登陆',U('Index/index'),0);
              $this->redirect(U('Index/index'),'',0, '请登录...');
              exit;
    	}

    }
    /*检查数组数据是否为空
    author：winter
    date：2015年9月28日19:41:41
    */
    public function checkDump($data){
    	foreach($data as $key=>$val){
            $data[$key] = trim($val);
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

    /**
    *系统给用户发送消息
    *@param id 用户id，
    *        type 1：系统消息  2：评论回复消息,
    *        time 格式化时间
    *        content 消息内容
    *        nickname 用户名
    *@author:winter
    *@version:2015年11月3日18:53:50
    */
    public function sendmsg($id,$content,$nickname,$type,$time = ''){
            $time = $time?$time:time();
            $id = intval($id);
            $user_msg = M('user_msg');
            $data['type'] = $type;
            $data['msg']  = $content;
            $data['instime'] = $time;
            $data['status'] = 2;
            $data['uid'] = $id;
            $data['username'] = $nickname;
            $sign = $user_msg->add($data);
            return $sign;
    }
    /**
    *记录管理员操作日志
    *@author winter
    *@version 2015年11月23日16:42:01
    */
    public function addadminlog($title,$sqlcontent,$type,$id,$idname){
        $data['adminid'] = session('uid');
        $data['name']    = session('name');
        $data['title']    = $title;
        $data['instime'] = time();
        $data['sqlcontent'] = $sqlcontent;
        $data['type']    = $type;
        $data[$idname]   = $id;
        $adminlog = M('admin_log');
        $adminlog->add($data);
        //echo $adminlog->getlastsql();die();
    }
}
?>