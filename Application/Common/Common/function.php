<?php

	function xxxLog($data){
		$fh = fopen('log.txt','a+');
		fwrite($fh, date('Y-m-d H:i:s')."\n");
		fwrite($fh, $data."\n\n");
		fclose($fh);
	}

	//ajax返回
	function ajaxReturn($errcode = 0, $msg = '', $data = array()){
	        $data = array('status'=>$errcode, 'msg'=>$msg, 'data'=>$data);
	        exit(json_encode($data, JSON_UNESCAPED_UNICODE));
	    }
		//微信
		function is_weixin(){   
			return strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false;
		}
		function isAjax() {
	    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 'xmlhttprequest' == strtolower($_SERVER['HTTP_X_REQUESTED_WITH']);
	}

	function errReturn($errCode,$msg){
	    ajaxReturn($errCode,$msg);
	}


	function get_client_ip1(){
	    $type       =  $type ? 1 : 0;
	    static $ip  =   NULL;
	    if ($ip !== NULL) return $ip[$type];
	    if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
	        $arr    =   explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
	        $pos    =   array_search('unknown',$arr);
	        if(false !== $pos) unset($arr[$pos]);
	        $ip     =   trim($arr[0]);
	    }elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
	        $ip     =   $_SERVER['HTTP_CLIENT_IP'];
	    }elseif (isset($_SERVER['REMOTE_ADDR'])) {
	        $ip     =   $_SERVER['REMOTE_ADDR'];
	    }
	    // IP地址合法验证
	    $long = sprintf("%u",ip2long($ip));
	    $ip   = $long ? array($ip, $long) : array('0.0.0.0', 0);
	    return $ip[$type];
	}

	function checkApiServerIp(){
	    $ip = get_client_ip();
	    $valid = array('115.28.147.141','114.215.156.219');
	    return in_array($ip, $valid);
	}


	function group($groupid){
		switch ($groupid) {
			case '1':
				return '红组';
			
			case '2':
				return '蓝组';
			case '3':
				return '绿组';
		}
	}
	function sexval($id){
		switch ($id) {
			case '1':
				return '男';
			case '2':
				return '女';
			
		}
	}

?>