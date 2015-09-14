<?php
require_once('../common/config.php');
mysql_connect(DB_HOST, DB_USER, DB_PSWD, DB_PORT) or die('mysql connect fail');
mysql_select_db(DB_NAME);
mysql_query('set names utf8');

$opid = trim($_POST['opid']);
$ip = get_client_ip();

if(!checkApiServerIp()){
    errReturn(Errorcode::$CLIENTIP_INVALID);
}

if(preg_match("/^[a-f\d]{32}$/",$opid)){
    $query = mysql_query('select name,concat("'.DOMAIN_PATH.'",headimg) as headimg,concat("'.DOMAIN_PATH.'",img) as img,votes from actors where opid="'.$opid.'"');
    $row = mysql_fetch_assoc($query);
    if(!empty($row)){
        ajaxReturn(0,'', $row);
    }    
}

errReturn(Errorcode::$OPID_ERROR);