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
   
}