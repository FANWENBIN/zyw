<?php

function isAjax() {
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 'xmlhttprequest' == strtolower($_SERVER['HTTP_X_REQUESTED_WITH']);
}

function is_weixin(){   
    return strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false;
}


function ajaxReturn($status = 0, $msg = '', $data = array()){
    header('Content-Type: application/json');
    $data = array('status'=>$status, 'msg'=>$msg, 'data'=>$data);
    exit(json_encode($data));
}


function errReturn($errCode){
    ajaxReturn($errCode, Errorcode::getErrorMsg($errCode));
}


function get_client_ip(){
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