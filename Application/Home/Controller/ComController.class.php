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
}