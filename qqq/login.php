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
require_once('config.php');
session_start();

//时间戳及回调地址session保存
$times= md5(date("YmdHis".get_client_ip()));
$callback_url = $_SERVER['HTTP_REFERER'];
$redirect_url =  "http://".$_SERVER["HTTP_HOST"].str_replace("login.php", "redirect.php", $_SERVER["REQUEST_URI"]);
$_SESSION["state"] = $times;
$_SESSION["redirect_url"] = $redirect_url;
$_SESSION["callback_url"] = $callback_url;

//获取oauth配置信息
$scope = array();
foreach($oauth["api"] as $key=>$val){
    if($val==1){
        $scope[] = $key;
    }
}
$param = array(
    "response_type"    => "code",
    "client_id"        =>    $oauth["appid"],
    "redirect_uri"    =>    $redirect_url,
    "scope"            =>    join(",", $scope),
    "state"            =>    $times
);
$_SESSION['appid'] = $oauth["appid"];
$_SESSION['appkey'] = $oauth["appkey"];

//合并请求URL
$get_array = array();
foreach($param as $key=>$val){
    $get_array[] = $key."=".urlencode($val);
}
$get_url = "https://graph.qq.com/oauth2.0/authorize?";
$get_url .= join("&",$get_array);

header("location:".$get_url);//第一步请求

/*if( $_GET['debug'] && $_GET['debug'] == "1"){
    echo "<html><table><tr><td class='narrow-label'>网站地址:</td><td><pre>".$get_url."</pre></td></tr>";
    echo "<tr><td class='narrow-label'>请求参数:</td><td><pre>".var_export($param,true)."</pre></td></tr>";
    echo "<tr><td class='narrow-label'></td><td><input type='button' onclick=\"location.href='".$get_url."';\" class='button' value='点此进入QQ登录' /></td></tr>";
    echo "</table></html>";
}else{
    header("location:".$get_url);
}*/
?>