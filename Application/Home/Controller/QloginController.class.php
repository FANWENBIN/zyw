<?php
namespace Home\Controller;
use Think\Controller;

/**
 * 登录注册
 * @author hxf
 * @version 2015年9月24日16:41:44
 */
class QloginController extends Controller {
    
   public function qqcallback(){

    require_once("./QQ/API/qqConnectAPI.php");
    $qc = new \QC();
    echo $qc->qq_callback();
    echo $qc->get_openid();
    var_dump($qc);
   }
    
}