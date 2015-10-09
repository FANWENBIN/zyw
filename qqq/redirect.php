<?php
header("Content-type:text/html; charset=UTF-8;");
/*==================================
'=类 名 称：QQConnect
'=功    能：QQ登录SDK For PHP
'=作    者：blog.unvs.cn
'=Q     Q: 775204432
'=日    期：2013-05-20
'=淘宝店铺：http://fxzdp.taobao.com（接做网站集成QQ、微博、淘宝等平台账号登录，程序可以是：ASP、PHP、NET）
'=SDK发布地址：http://www.yiit.cn/plugin/qq-php-sdk-demo.html（使用疑难问题，可留言提出）
'==================================
'转载分享请保留以上内容，谢谢！！*/
require_once('function.php');
session_start();

if( !isset($_GET["state"])||empty($_GET["state"])||!isset($_GET["code"])||empty($_GET["code"]) )
{
	echo "第三方登录获取参数失败。<br />";
}
else
{
	//参数验证
	if( $_GET["state"]!=$_SESSION["state"] )
	{
		//echo "网站获取用于第三方应用防止CSRF攻击失败。";
		echo "<span style='font-size:12px;line-height:24px;'>请求非法或超时!&nbsp;&nbsp;<a href='../index.php'>返回首页</a></span>";
		exit;
	}
	$get_url = "https://graph.qq.com/oauth2.0/token";
	$get_param = array(
			"grant_type"     =>    "authorization_code",
			"client_id"      =>    $_SESSION['appid'],
			"client_secret"  =>    $_SESSION['appkey'],
			"code"           =>    $_GET["code"],
			"state"          =>    $_GET["state"],
			"redirect_uri"   =>    $_SESSION["redirect_url"]
	);
	//unset($_SESSION["state"]);
	unset($_SESSION["redirect_url"]);

	$content = get($get_url,$get_param);//获取到access_token，回调结果示例：access_token=8D947438649139555283F4DDE4EEBDE8&expires_in=7776000

	//判断返回结果，保存access_token，继续操作
	if( $content && $content!==FALSE)
	{
		$temp = explode("&", $content);
		$param = array();
		foreach($temp as $val)
		{
			$temp2 = explode("=", $val);
			$param[$temp2[0]] = $temp2[1];
		}
		$_SESSION["access_token"] = $param["access_token"];
		$get_url = "https://graph.qq.com/oauth2.0/me";
		$get_param = array(
				"access_token"    => $param["access_token"]
		);
		$content = get($get_url, $get_param);//获取到openid，回调结果示例：callback( {"client_id":"100225699","openid":"3757FD1A8129F3CD1953F96B259BD946"} );
		
		//判断返回结果，保存openid，返回页面
		if( $content && $content!==FALSE )
		{
			$random = get_random(6);//6位随机数
			
			$temp = array();
			preg_match('/callback\(\s+(.*?)\s+\)/i', $content, $temp);
			$result = json_decode($temp[1],true);
			
			$openid = $result["openid"];
			$_SESSION["oauth_pass"] = $random.strtolower(substr($openid, 2, 2));
			$_SESSION["oauth_openid"] = $openid;
			$email = strtolower(substr($openid, 2, 8))."@qq.com";
			
			
			//调用get_user_info接口，获取用户基本信息，并Session保存
			if( $result["openid"] && !empty($result["openid"]) && !empty($param["access_token"]) )
			{
				$get_url = "https://graph.qq.com/user/get_user_info";
				$get_param = array(
					"access_token"	=>	$param["access_token"],
					"oauth_consumer_key"	=>	$_SESSION['appid'],
					"openid"	=>	$result["openid"]
				);
								
				unset($_SESSION['appid']);
				unset($_SESSION['appkey']);
				$token = $param["access_token"];
				
				$content = get($get_url, $get_param);//请求用户信息
				$result = json_decode($content,true);
				
				//判断返回结果，保存session，下一步操作
				if( $result && $result['ret'] == 0 )
				{
					$userid = trim($result['nickname']);
					$userimg = $result['figureurl_2'];
					
					$userid = $uname = trim($result['nickname']);

					echo "您的昵称：".$userid."<br/>";
					echo "您的头像地址：".$userimg."<br/>";
					echo "您的唯一OPENID：".$openid;
					//header($gourl);exit;
				}
				else
				{
					echo "用户信息请求错误，错误代码：".$result['ret']."；错误信息：".$result['msg'];
				}
			}
			
			//调式使用
			/*if( $_GET['debug'] && $_GET['debug'] == "1"){
				echo "<tr><td class='narrow-label'></td><td><input type='button' onclick='location.href=\"../../index.php\";' class='button' value='点此返回首页' /></td></tr>";
			}else{
				echo "<script language=javascript>window.close();opener.location.href=opener.location.href;</script>";
			}*/
		}
	}
}

?>