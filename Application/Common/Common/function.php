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
        exit(json_encode($data));
    }
	//微信
	function is_weixin(){   
		return strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false;
	}

?>