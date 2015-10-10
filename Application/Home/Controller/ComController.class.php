<?php
// 本类由系统自动生成，仅供测试用途
namespace Home\Controller;
use Think\Controller;
class ComController extends Controller {
    public function index(){
	echo  '中演网';
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
   
}