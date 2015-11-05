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
	function vediotyp($id){
		switch ($id) {
			case '1':
				return '电影电视';
			case '2':
				return '制作花絮';
			case '3':
				return '影视教学';
			case '4':
				return '探班周边';
			case '5':
				return '娱乐头条';
			case '6':
				return '影视资讯';
		}
	}
	/**
	*判断系统消息是否已发送到用户
	* @author：winter
	* @version:2015年11月3日13:07:37
	*/
	function finshTime($time){
		return $time > time() ? '未发送' : '已发送';
	
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
    	if(empty($time)){
    		return '';
    	}
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
    //根据id获取推荐团身份
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

	function newtype($id){
		switch ($id) {
			case '1':
				return '今日焦点';

			case '2':
				return '星闻动向';
			
			case '3':
				return '艺术中国梦';
			
			default:
				return '123';
			
		}
	}
// 检测输入的验证码是否正确，$code为用户输入的验证码字符串
    function check_verify($code, $id = '')
    {    
        $verify = new \Think\Verify();    
        return $verify->check($code, $id);
    }
    //处理名次上升下降
    function ranking($num){
    	if($num > 0){
    		return "<sup>".abs($num)."</sup>";
    	}elseif ($num == 0) {
    		return "<span></span>";
    	}elseif ($num < 0) {
    		return "<sub>".abs($num)."</sub>";
    	}
    }
    /** 
 * created by 2261617274@qq.com at 2015-10-15 15:18:06
 * 汉字拼音首字母工具类 
 *  注：英文的字串：不变返回(包括数字)    eg .abc123 => abc123 
 *      中文字符串：返回拼音首字符        eg. 王小明 => W
 *      中英混合串: 返回拼音首字符和英文   eg. 我i我j => W
 *  eg. 
 *  author：winter
 *  getFirstCharter('王小明'); 
 */  
function getFirstCharter($str){
     if(empty($str)){return '';}
     $fchar=ord($str{0});
     if($fchar>=ord('A')&&$fchar<=ord('z')) return strtoupper($str{0});
     $s1=iconv('UTF-8','gb2312',$str);
     $s2=iconv('gb2312','UTF-8',$s1);
     $s=$s2==$str?$s1:$str;
     $asc=ord($s{0})*256+ord($s{1})-65536;
     if($asc>=-20319&&$asc<=-20284) return 'A';
     if($asc>=-20283&&$asc<=-19776) return 'B';
     if($asc>=-19775&&$asc<=-19219) return 'C';
     if($asc>=-19218&&$asc<=-18711) return 'D';
     if($asc>=-18710&&$asc<=-18527) return 'E';
     if($asc>=-18526&&$asc<=-18240) return 'F';
     if($asc>=-18239&&$asc<=-17923) return 'G';
     if($asc>=-17922&&$asc<=-17418) return 'H';
     if($asc>=-17417&&$asc<=-16475) return 'J';
     if($asc>=-16474&&$asc<=-16213) return 'K';
     if($asc>=-16212&&$asc<=-15641) return 'L';
     if($asc>=-15640&&$asc<=-15166) return 'M';
     if($asc>=-15165&&$asc<=-14923) return 'N';
     if($asc>=-14922&&$asc<=-14915) return 'O';
     if($asc>=-14914&&$asc<=-14631) return 'P';
     if($asc>=-14630&&$asc<=-14150) return 'Q';
     if($asc>=-14149&&$asc<=-14091) return 'R';
     if($asc>=-14090&&$asc<=-13319) return 'S';
     if($asc>=-13318&&$asc<=-12839) return 'T';
     if($asc>=-12838&&$asc<=-12557) return 'W';
     if($asc>=-12556&&$asc<=-11848) return 'X';
     if($asc>=-11847&&$asc<=-11056) return 'Y';
     if($asc>=-11055&&$asc<=-10247) return 'Z';
     return S;
 }
	/**
	*截取提交url路径的参数
	*@param url "http://localhost/zyw/index.php?m=Home&c=News&a=news_details&id=71"
	*@version 2015年11月4日17:38:27
	*@author witner
	*@return param  Array ( [m] => Home [c] => News [a] => news_details [id] => 71 )
	*/
	function getUrlParam($url){
		//$url = "http://localhost/zyw/index.php?m=Home&c=News&a=news_details&id=71";
        $str = explode('?', $url);
        $logy = explode('&', $str[1]);
        foreach($logy as $key=>$val){
            $swop = explode('=', $val);
            $param[$swop[0]] = $swop[1];
        }
        return $param;
	}

?>