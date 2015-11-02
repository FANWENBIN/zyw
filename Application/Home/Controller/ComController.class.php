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
        $data['id'] = session('uid');
        $data['name'] = session('name');
        $user = M('user');
        $list = $user->where($data)->find();
        if(!$list){
            $this->error('请先登录',U('Index/index'));  //从用户中心返回首页
        }
    }
    //外部验证登陆返回上一层
    public function checkLogin(){
        //md5(xxzyw916);
        $data['id'] = session('uid');
        $data['nickname'] = session('name');
        $user = M('user');
        $list = $user->where($data)->find();
        if(!$list){
            $this->error('请先登录');  //error 返回上一层
        }
    }
//测试
    public function test(){

        $url = 'http://m2.nadoo.cn/p/zyw/index.php?m=Home&c=Vote&a=actorinfo';
        $data = array('opid'=>'3099502f8652e48cd2d15e49bb5bf67f','wxopenid'=>'ox9LYshHRsmsTzCOjJjmcO6N-7VA');
    	$a =  $this->htcurl($url,$data);
      //var_dump($a);
    }
//post测试接口
     public function htcurl($url,$data){
    	 $url = $url;
    	 $post_data = $data;
    	 $ch = curl_init();
    	 curl_setopt($ch, CURLOPT_URL, $url);
    	 curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    	 // post数据
    	 curl_setopt($ch, CURLOPT_POST, 1);
    	 // post的变量
    	 curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    	 $output = curl_exec($ch);
    	 curl_close($ch);
    	 //打印获得的数据
    	 print_r($output);
    }
    /** 
    * 获取本周第一天/最后一天的时间戳 
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
   
}
   
