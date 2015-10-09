<?php
header("Content-type:text/html; charset=UTF-8;");
/*==================================
'=�� �� �ƣ�QQConnect
'=��    �ܣ�QQ��¼SDK For PHP
'=��    �ߣ�blog.unvs.cn
'=Q     Q: 775204432
'=��    �ڣ�2013-05-20
'=�Ա����̣�http://fxzdp.taobao.com��������վ����QQ��΢�����Ա���ƽ̨�˺ŵ�¼����������ǣ�ASP��PHP��NET��
'=SDK������ַ��http://www.yiit.cn/plugin/qq-php-sdk-demo.html��ʹ���������⣬�����������
'==================================
'ת�ط����뱣���������ݣ�лл����*/
require_once('function.php');
require_once('config.php');
session_start();

//ʱ������ص���ַsession����
$times= md5(date("YmdHis".get_client_ip()));
$callback_url = $_SERVER['HTTP_REFERER'];
$redirect_url =  "http://".$_SERVER["HTTP_HOST"].str_replace("login.php", "redirect.php", $_SERVER["REQUEST_URI"]);
$_SESSION["state"] = $times;
$_SESSION["redirect_url"] = $redirect_url;
$_SESSION["callback_url"] = $callback_url;

//��ȡoauth������Ϣ
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

//�ϲ�����URL
$get_array = array();
foreach($param as $key=>$val){
    $get_array[] = $key."=".urlencode($val);
}
$get_url = "https://graph.qq.com/oauth2.0/authorize?";
$get_url .= join("&",$get_array);

header("location:".$get_url);//��һ������

/*if( $_GET['debug'] && $_GET['debug'] == "1"){
    echo "<html><table><tr><td class='narrow-label'>��վ��ַ:</td><td><pre>".$get_url."</pre></td></tr>";
    echo "<tr><td class='narrow-label'>�������:</td><td><pre>".var_export($param,true)."</pre></td></tr>";
    echo "<tr><td class='narrow-label'></td><td><input type='button' onclick=\"location.href='".$get_url."';\" class='button' value='��˽���QQ��¼' /></td></tr>";
    echo "</table></html>";
}else{
    header("location:".$get_url);
}*/
?>