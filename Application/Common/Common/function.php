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
	        exit(json_encode($data,JSON_UNESCAPED_UNICODE));
	}
	//微信
	function is_weixin(){   
		return strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false;
	}
	function isAjax() {
    	return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 'xmlhttprequest' == strtolower($_SERVER['HTTP_X_REQUESTED_WITH']);
	}

	function errReturn($errCode,$msg){
	    ajaxReturn($errCode,$msg);
	}


	function get_client_ip1(){
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


	function group($groupid){
		switch ($groupid) {
			case '1':
				return '红组';
			
			case '2':
				return '蓝组';
			case '3':
				return '绿组';
		}
	}
	function sexval($id){
		switch ($id) {
			case '1':
				return '男';
			case '2':
				return '女';
			
		}
	}
    /**
     * 获取文件路径
     * @author huqinlou
     * @version 2015年6月9日 下午5:04:35
     */
    function get_attach_path($path){
        return 'http://'.$_SERVER['HTTP_HOST'].__ROOT__.$path;
    }
    /**
     * 简单格式化时间
     * @param int $time 时间戳
     * @author 胡勤楼
     * 2014-7-31 下午6:32:08
     */
    function hql_date($time){
    	empty($time)&&($time=time());
    	return date('Y-m-d H:i',$time);
    }
    /**
     * 简单格式化年月日
     * param ： int $time 时间戳
     * author： winter
     * date  :2015年9月25日14:00:51
     */
    function winter_date($time){
    	empty($time)&&($time=time());
    	return date('Y-m-d',$time);
    }
    /**
     * 获取文件相对路径
     * @author hxf
     * @version 2015年8月21日 14:39:55
     */
    function get_relative_path($path){
        return substr($path,strlen('http://'.$_SERVER['HTTP_HOST'].__ROOT__));
    }
	function comtype($id){
		switch ($id) {
			case '1':
				return '艺术家';
			case '2':
				return '导演';
			case '3':
				return '制片人';
			case '4':
				return '编剧';
		}
	}
	
// 检测输入的验证码是否正确，$code为用户输入的验证码字符串
    function check_verify($code, $id = '')
    {    
        $verify = new \Think\Verify();    
        return $verify->check($code, $id);
    }
    //处理名次上升下降
    public function ranking($num){
    	if($num > 0){
    		return "<sub>".abs($num)."</sub>";
    	}elseif ($num == 0) {
    		return "<span></span>";
    	}elseif ($num < 0) {
    		return "<sup>".abs($num)."</sup>";
    	}
    }

?>