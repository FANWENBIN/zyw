<?php
// 本类由系统自动生成，仅供测试用途
namespace Home\Controller;
use Think\Controller;
class VoteController extends ComController {
    public function index(){
		//$this->display('vote');
		echo $ip = $this->get_client_ip();
    }


function get_client_ip($type = 0) {
    $type       =  $type ? 1 : 0;
    static $ip  =   NULL;
    if ($ip !== NULL) return $ip[$type];
    if($_SERVER['HTTP_X_REAL_IP']){//nginx 代理模式下，获取客户端真实IP
        $ip=$_SERVER['HTTP_X_REAL_IP'];     
    }elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {//客户端的ip
        $ip     =   $_SERVER['HTTP_CLIENT_IP'];
    }elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {//浏览当前页面的用户计算机的网关
        $arr    =   explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        $pos    =   array_search('unknown',$arr);
        if(false !== $pos) unset($arr[$pos]);
        $ip     =   trim($arr[0]);
    }elseif (isset($_SERVER['REMOTE_ADDR'])) {
        $ip     =   $_SERVER['REMOTE_ADDR'];//浏览当前页面的用户计算机的ip地址
    }else{
        $ip=$_SERVER['REMOTE_ADDR'];
    }
    // IP地址合法验证
    $long = sprintf("%u",ip2long($ip));
    $ip   = $long ? array($ip, $long) : array('0.0.0.0', 0);
    return $ip[$type];
 }

    public function voting(){
    	$opid = trim($_POST['opid']);
		$openid = addslashes(trim($_POST['wxopenid']));
		$ip = get_client_ip();

		if(!checkApiServerIp()){
		    errReturn(Errorcode::$CLIENTIP_INVALID);
		}

		if(preg_match("/^[a-f\d]{32}$/",$opid)){
		    //查询该openid是否投过票
		    $query = mysql_query('select id from votelog where wxopenid="'.$openid.'" and insdate="'.date('Y-m-d').'"');
		    if(mysql_num_rows($query) == 0){
		        mysql_query('update actors set votes=votes+1 where opid="'.$opid.'"');
		        if(mysql_affected_rows()){
		            $query = mysql_query('insert into votelog (actor_opid,wxopenid,ip,instime,insdate) values ("'.$opid.'","'.$openid.'","'.$ip.'",'.time().',"'.date('Y-m-d').'")');
		            //echo 'insert into votelog (actor_opid,wxopenid,ip,instime,insdate) values ("'.$opid.'","'.$openid.'","'.$ip.'",'.time().',"'.date('Y-m-d').'")';
		            if($query){
		                $query = mysql_query('select votes from actors where opid="'.$opid.'"');
		                $row = mysql_fetch_assoc($query);
		                ajaxReturn(0,'', $row);
		            }else{
		                mysql_query('update actors set votes=votes-1 where opid="'.$opid.'"');
		                ajaxReturn(1,'投票失败');
		            }
		            
		        }
		        
		    }else{
		        errReturn(Errorcode::$DAYS_VOTE_OUT_LIMIT);
		    }
		    
		}
    }
}