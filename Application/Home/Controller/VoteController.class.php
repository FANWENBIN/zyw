<?php
// 本类由系统自动生成，仅供测试用途
namespace Home\Controller;
use Think\Controller;
class VoteController extends ComController {
    public function index(){
		$this->display('vote');
		//echo $ip = get_client_ip();
    }


	/*投票接口*/
    public function voting(){
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
    }

	/*二维码生成*/
    public function code(){
    	 vendor("phpqrcode.phpqrcode");
    	 //http://yz2500.gov.cn/TP/actor/Index/index.php?opid=3099502f8652e48cd2d15e49bb5bf67f
    		$opid = I('get.opid');
            $data = 'http://yz2500.gov.cn/TP/actor/Index/index.php?opid='.$opid;
            // 纠错级别：L、M、Q、H
            $level = 'L';
            // 点的大小：1到10,用于手机端4就可以了
            $size = 4;
            // 下面注释了把二维码图片保存到本地的代码,如果要保存图片,用$fileName替换第二个参数false
           // $path = __PUBLIC__."/images/";
            // 生成的文件名
            //$fileName = $path.$size.'.png';
            \QRcode::png($data, false, $level, $size);
           // echo '';
            exit;
    }
}