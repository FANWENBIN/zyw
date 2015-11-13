<?php
// 
namespace Home\Controller;
use Think\Controller;
class ComController extends Controller {
//初始化构造
    public function __construct(){
    parent::__construct();
	   $config = M('configure');
       $records = $config->where('type = 4')->find();
       $this->assign('records',$records['records']);
       $this->assign('copyright',$records['stagerule']);
       //加载导航栏
       $this->nav();
    }
//导航栏
    public function nav(){
        $nav = M('nav');
        $list = $nav->where('status = 1')->order('place')->select();
        $this->assign('nav',$list);

    }
    //用户中心登陆验证直接返回首页重新登陆
    public function checkuserLogin(){
        //md5(xxzyw916);
        $data['id']     = session('userid');
        $data['mobile'] = session('userphone');
        $user = M('user');
        $list = $user->where($data)->find();
        if(!$list){
           //return 0; //从用户中心返回首页
           $this->error('请登录',U('Index/index'));
        }else{
            return $list;
        }
    }
    //外部验证登陆返回上一层
    public function checkLogin(){
        //md5(xxzyw916);
        $data['id'] = session('userid');
        $data['mobile'] = session('userphone');
        $user = M('user');
        $list = $user->where($data)->find();
        if(!$list){
            $this->error('请先登录');  //error 返回上一层
        }
    }
    /**
    *系统消息提示和评论回复消息提示
    *@author winter
    *@version 2015年11月13日18:57:22
    */
    public function mssage(){
        $user_msg = M('user_msg');
        $data['status'] = 2;
        $data['uid'] = session('userid');
        //所有未读消息
        $this->sum = $user_msg->where($data)->count();
        $data['type'] = 1;
        $this->syssum = $user_msg->where($data)->count();//未读系统消息
        $data['type'] = 2;
        $this->usersum = $user_msg->where($data)->count();//用户评论回复消息

    }
    /** 
    * 获取本周第一天/最后一天的时间戳 
    * @author winter
    * @param string $type 
    * @return integer 
    * 
    */ 
    public function get_week_time( $type = 'first' ) {  
     /* 获取本周第一天/最后一天的时间戳 */ 
        $year = date( "Y" );  
        $month = date( "m" );  
        $day = date( 'w' );  
        $nowMonthDay = date( "t" );  
        if ( $type == 'first' ) {  
            $firstday = date( 'd' ) - $day+1;  
            if ( substr( $firstday, 0, 1 ) == "-" ) {  
                $firstMonth = $month - 1;  
                $lastMonthDay = date( "t", $firstMonth );  
                $firstday = $lastMonthDay - substr( $firstday, 1 );  
                $time_1 = strtotime( $year . "-" . $firstMonth . "-" . $firstday );  
            } else {  
                $time_1 = strtotime( $year . "-" . $month . "-" . $firstday );  
            }  
            return $time_1;  
        } else {  
            $lastday = date( 'd' ) + (7 - $day)+1;  
            if ( $lastday > $nowMonthDay ) {  
                $lastday = $lastday - $nowMonthDay;  
                $lastMonth = $month + 1;  
                $time_2 = strtotime( $year . "-" . $lastMonth . "-" . $lastday );  
            } else {
                $time_2 = strtotime( $year . "-" . $month . "-" . $lastday );  
            }
            return $time_2;
            }  
    } 
     /**
     *判断活动是否有周末
     * @author winter
     * @version 2015年10月10日17:36:27
     * @param begin 开始时间
     * @param last  结束时间
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
    /*
    参数说明：$cate:查询数据
        $pid 是父级id
        $level 是下一级的前置空格数
        $html是设施前置留空白还是'--'
        $fid  父id字段
        $id  id字段
    //本方法返回值为数组、可调节性高。个性化设置~~~方法大家随便用。
    //$tree=$user->sortOut($val,0,0,'---','fid','a_id');
    */
    public function sortOut($cate,$pid=0,$level=0,$html='--',$fid='fid',$id='a_id'){
        $tree = array();
        foreach($cate as $v){
            if($v[$fid] == $pid){
                $v['level'] = $level + 1;
                $v['html'] = str_repeat($html, $level);
                $tree[] = $v;
                $tree = array_merge($tree,$this->sortOut($cate,$v[$id],$level+1,$html,$fid,$id));
            }
        }
        return $tree;
    }

    /*根据浏览几率猜你喜欢
    author：winter
    date：2015年10月27日18:42:56
    */
    public function like(){
        $vedio  = session('video');
        $actors = session('actors');
        $active = session('active');
        $news   = session('news');
        $sqlvedio  = M('vedio');
        $sqlactors = M('actors');
        $sqlactive = M('active');
        $sqlnews   = M('news');
        //喜欢视频
        if(!empty($vedio)){
            $vewhere['status'] = 1;
            $vewhere['type'] = $vedio['type'];
            $veval = $sqlvedio->where($vewhere)->order('hot desc,instime desc,recommend desc')->limit(0,3)->select();
        }else{
            $vewhere['status'] = 1;
            $veval = $sqlvedio->where($vewhere)->order('hot desc,instime desc,recommend desc')->limit(0,3)->select();
        }
        //喜欢的新闻和新闻
        if(!empty($news)){
            $newhere['status'] = 1;
            $newhere['type']   = $news['type'];
            $acval = $sqlnews->where($newhere)->order('hot desc,instime desc,`order` desc')->limit(0,4)->select();
        }
        //喜欢明星动态
        if(!empty($actors)){
            $acwhere['status'] = 1;
            $acwhere['keywords'] = array('like','%'.$actors['name'].'%');
            $acval = $sqlnews->where($acwhere)->order('hot desc,instime desc,`order` desc')->limit(0,4)->select();
            if(!$acval){
                if(!empty($news)){
                    $newhere['status'] = 1;
                    $newhere['type']   = $news['type'];
                    $acval = $sqlnews->where($newhere)->order('hot desc,instime desc,`order` desc')->limit(0,4)->select();
                }else{
                    $acwhere2['status'] = 1;
                $acval = $sqlnews->where($acwhere2)->order('hot desc,instime desc,`order` desc')->limit(0,4)->select();
                }
            }
           
        }else{
            if(!empty($news)){
                    $newhere['status'] = 1;
                    $newhere['type']   = $news['type'];
                    $acval = $sqlnews->where($newhere)->order('hot desc,instime desc,`order` desc')->limit(0,4)->select();
                }else{
                    $acwhere2['status'] = 1;
                $acval = $sqlnews->where($acwhere2)->order('hot desc,instime desc,`order` desc')->limit(0,4)->select();
                }
            
        }
        //喜欢的活动
        if(!empty($active)){
            $actwhere['status'] = 1;
           
            $actval = $sqlactive->where($actwhere)->order('concern desc,instime desc')->limit(0,2)->select();
        }else{
            $actwhere['status'] = 1;
            $actval = $sqlactive->where($actwhere)->order('concern desc,instime desc')->limit(0,2)->select();
        }
        $this->assign('veval',$veval);
        $this->assign('acval',$acval);
        $this->assign('actval',$actval);
     

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
    /*第一信息短信发送公共方法
    author：winter
    date：2015年10月12日17:47:13
    */
    public function sms($phone,$verify){
        header("Content-Type: text/html; charset=UTF-8");
        $verify = $verify;//获取随机验证码        
        //以下信息自己填以下
        $mobile=$phone;//手机号
        $flag = 0; 
        $params = '';
        $argv = array( 
            'name'=>'yangxiaoli',     //必填参数。用户账号
            'pwd'=>'A1AE8E481290FA1930641F7A72A6',     //必填参数。（web平台：基本资料中的接口密码）
            //'content'=>'短信验证码为：'.$verify.'，请勿将验证码提供给他人。', 
              //必填参数。发送内容（1-500 个汉字）UTF-8编码
            'content'=>$verify.'(您的中国好演员短信验证码)，有效期为30分钟，请勿将该验证码提供给他人。如非本人操作，请忽略此短信',
            //【中国电视好演员网】
            'mobile'=>$mobile,   //必填参数。手机号码。多个以英文逗号隔开
            'stime'=>'',   //可选参数。发送时间，填写时已填写的时间发送，不填时为当前时间发送
            'sign'=>'中国好演员网',    //必填参数。用户签名。
            'type'=>'pt',  //必填参数。固定值 pt
            'extno'=>''    //可选参数，扩展码，用户定义扩展码，只能为数字
        ); 
        //print_r($argv);exit;
        //构造要post的字符串 
        //echo $argv['content'];
        foreach ($argv as $key=>$value) { 
            if ($flag!=0) { 
                $params .= "&"; 
                $flag = 1; 
            } 
            $params.= $key."="; 
            $params.= urlencode($value);// urlencode($value); 
            $flag = 1; 
        } 
        $url = "http://sms.1xinxi.cn/asmx/smsservice.aspx?".$params; //提交的url地址
        //$con= substr( file_get_contents($url), 0, 1 );  //获取信息发送后的状态
        $con = file_get_contents($url);
        $con = explode(',', $con);
        if($con[0] == 0){
            return 0;
        }else{
            return 1;
        }
         
    }
    /**
    *登陆注册、用户信息判断显示
    *@author：winter
    *@version：2015年11月4日13:34:48
    */
    public function userinfo(){
        if(session('userid')&&session('username')&&session('userphone')){
            //var_dump(1);
            $user = M('user');
            //exit($user);
            $this->$userinfo = M('user')->where('id='.session('userid'))->find();
            $this->$status = 1;
        }else{
            $this->$status = 0;
        }
    }
    /**
    *上传
    *@author winter
    *@version 2015年11月6日16:49:20
    */
    public function uploadimg(){
       
     //等待开发中
        
    }
   
}
   
