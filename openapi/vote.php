<?php
require_once('../common/config.php');
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

errReturn(Errorcode::$OPID_ERROR);