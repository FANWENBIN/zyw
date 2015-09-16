<?php

	function xxxLog($data){
		$fh = fopen('log.txt','a+');
		fwrite($fh, date('Y-m-d H:i:s')."\n");
		fwrite($fh, $data."\n\n");
		fclose($fh);
	}
	//=================惠管家短信==========start
	//配置信息
	function sendSMS($mobiles,$content){
	xxxLog('开始发送短信');
	$isSingle = true;
		//$res = sendSMS(15905199150,'测试');
		if(trim($mobiles) == ''){
			return false;
		}
		$type = $isSingle ? 'single' : 'batch';
		
		$postdata = array(
			'method' => $type,
			'username' => 'zs00486',
			'password' => md5('t8hdqizg'),
			'mobiles' => $mobiles,
			'contents' => $content
		);
		$api = 'http://sms.3wxt.cn/servlet/SendSMS';
		//$api = 'http://sms.3wxt.cn/servlet/SendSMSmt?method='.$type.'&username=zs00463&password=zb0uuzeg&mobiles='.$mobiles.'&contents='.rawurlencode($content);
		$_res =curl_get_contents($api,$postdata);
		$res = explode(',', $_res);
		if($res[0] == '111'){
			xxxLog('短信发送成功');
			$send=M('send');
			$data['info']=$content;
			$data['date']=time();
			$data['type']='充值提示';
			$data['fanhui']=$_res;
			$data['status']=1;
			$data['phone']=$mobiles;
			$send->add($data);
			xxxLog($send->getLastSql());
			return true;
		}
		xxxLog('短信发送失败');
		$send=M('send');
		$data['info']='短信发送失败';
		$data['date']=time();
		$data['type']='充值';
		$data['fanhui']=$_res;
		$data['status']=0;
		$data['phone']=$mobiles;
		$send->add($data);
		xxxLog($send->getLastSql());
		return false;
	}

	function curl_get_contents($url = '', $post_data = array(),$timeout = 10){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);

		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); // 从证书中检查SSL加密算法是否存在
		//curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
		curl_setopt($ch, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
		curl_setopt($ch, CURLOPT_TIMEOUT, $timeout); // 设置超时限制防止死循环
		curl_setopt($ch, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回


		if(!empty($post_data)){
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
		}
		$output = curl_exec($ch);
		curl_close($ch);
		return $output;
	}
	//======================惠管家end=============//
	//微信
	function is_weixin(){   
		return strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false;
	}

?>