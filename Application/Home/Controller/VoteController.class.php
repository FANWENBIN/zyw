<?php
// 本类由系统自动生成，仅供测试用途
namespace Home\Controller;
use Think\Controller;
class VoteController extends ComController {
    public function index(){
		$this->display('vote');
		//echo $ip = get_client_ip();
    }


	/*投票接口*/
    public function voting(){
    	define('DB_HOST','121.41.101.8');
		define('DB_USER','nadoocomp');
		define('DB_PSWD','nadoom2db#!^');
		define('DB_NAME','zyw');
		define('DB_PORT','3306');
    	mysql_connect(DB_HOST, DB_USER, DB_PSWD, DB_PORT) or die('mysql connect fail');
		mysql_select_db(DB_NAME);
		mysql_query('set names utf8');
    	$opid = trim($_POST['opid']);
		$openid = addslashes(trim($_POST['wxopenid']));
		$ip = get_client_ip();
		if(!checkApiServerIp()){
		    errReturn(Errorcode::$CLIENTIP_INVALID);
		}
		if(preg_match("/^[a-f\d]{32}$/",$opid)){
		    //查询该openid是否投过票
		    $query = mysql_query('select id from zyw_votelog where wxopenid="'.$openid.'" and insdate="'.date('Y-m-d').'"');
		    if(mysql_num_rows($query) == 0){
		        mysql_query('update zyw_actors set votes=votes+1 where opid="'.$opid.'"');
		        if(mysql_affected_rows()){
		            $query = mysql_query('insert into zyw_votelog (actor_opid,wxopenid,ip,instime,insdate) values ("'.$opid.'","'.$openid.'","'.$ip.'",'.time().',"'.date('Y-m-d').'")');
		            //echo 'insert into zyw_votelog (actor_opid,wxopenid,ip,instime,insdate) values ("'.$opid.'","'.$openid.'","'.$ip.'",'.time().',"'.date('Y-m-d').'")';
		            if($query){
		                $query = mysql_query('select votes from zyw_actors where opid="'.$opid.'"');
		                $row = mysql_fetch_assoc($query);
		                ajaxReturn(0,'', $row);
		            }else{
		                mysql_query('update zyw_actors set votes=votes-1 where opid="'.$opid.'"');
		                ajaxReturn(1,'投票失败');
		            }  
		        }
		        
		    }else{
		        errReturn(Errorcode::$DAYS_VOTE_OUT_LIMIT);
		    }
		    
		}
    }

	/*二维码生成*/
    public function code(){
    	 vendor("phpqrcode.phpqrcode");
    	 //http://yz2500.gov.cn/TP/actor/Index/index.php?opid=3099502f8652e48cd2d15e49bb5bf67f
    		$opid = I('get.opid');
            $data = 'http://yz2500.gov.cn/TP/actor/Index/index.php?opid='.$opid;
            // 纠错级别：L、M、Q、H
            $level = 'L';
            // 点的大小：1到10,用于手机端4就可以了
            $size = 4;
            // 下面注释了把二维码图片保存到本地的代码,如果要保存图片,用$fileName替换第二个参数false
           // $path = __PUBLIC__."/images/";
            // 生成的文件名
            //$fileName = $path.$size.'.png';
            \QRcode::png($data, false, $level, $size);
           // echo '';
            exit;
    }
    
    public function test(){
        $url = 'http://m2.nadoo.cn/p/zyw?index.php&m=Home&c=Vote&a=voting';
        $data = array('opid'=>'dc6e753bc18d9928773f7c30eee6ddbe','wxopenid'=>'ox9LYshHRsmsTzCOjJjmcO6N-7VA');
       $a =  $this->htcurl($url,$data);
       var_dump($a);
    }
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