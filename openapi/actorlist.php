<?php
require_once('../common/config.php');
mysql_connect(DB_HOST, DB_USER, DB_PSWD, DB_PORT) or die('mysql connect fail');
mysql_select_db(DB_NAME);
mysql_query('set names utf8');

$sign = trim($_POST['sign']);
list($sign, $time) = explode('.', $sign);
if(md5('55f0fa9121e1f'.$time.'55f0fac500259') !== $sign || abs(time() - $time) > 600){
    errReturn(Errorcode::$INVALID_SIGN);
}


$offset = isset($_POST['offset']) ? max(0,intval($_POST['offset'])) : 0;
$count = isset($_POST['count']) ? min(1000,max(1,intval($_POST['count']))) : 10;
$orderby = isset($_POST['orderby']) ? trim($_POST['orderby']) : '';
$ordertype = isset($_POST['ordertype']) && $_POST['ordertype'] == 'desc' ? 'desc' : 'asc';
$groupid = isset($_POST['groupid']) ? intval($_POST['groupid']) : 0;
$sex = isset($_POST['sex']) ? intval($_POST['sex']) : 0;

if(!in_array($orderby, array('name', 'votes', ''))){
    ajaxReturn(1,'orderby 参数不合法');
}

if(!$orderby) $orderby = 'id';


if(!checkApiServerIp()){
    errReturn(Errorcode::$CLIENTIP_INVALID);
}

$where = array();
if($groupid > 0){
    $where[] = 'groupid='.$groupid;
}
if($sex > 0){
    $where[] = 'sex='.$sex;
}
if(!empty($where)){
    $where = ' where '.implode(' and ', $where);
}else{
    $where = '';
}

$query = mysql_query('select name,concat("'.DOMAIN_PATH.'",headimg) as headimg,concat("'.DOMAIN_PATH.'",img) as img,votes,groupid,sex from actors '.$where.' order by '.$orderby.' '.$ordertype.' limit '.$offset.','.$count);
$data = array();
while($row = mysql_fetch_assoc($query)){
    $data[] = $row;
}

$query = mysql_query('select count(id) as c from actors'.$where);
$row = mysql_fetch_assoc($query);
ajaxReturn(0,'', array('total'=>intval($row['c']), 'list'=>$data));  