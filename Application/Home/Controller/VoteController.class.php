<?php
// 本类由系统自动生成，仅供测试用途
namespace Home\Controller;
use Think\Controller;
class VoteController extends ComController {
    public function index(){
        $actors = M('actors');
        //好演员评选
        $actorsval = $actors->order('votes desc')->limit('0,8')->select();
        $this->assign('actors',$actorsval);

        //形象指数
        $actorsvalue = $actors->order('votes desc')->limit('0,4')->select();
        $this->assign('list',$actorsvalue);

		$this->display('vote');
		//echo $ip = get_client_ip();
    }

    //全部演员数据接口
    public function actorsres(){
        $actors = M('actors');
        $groupid = I('get.groupid');
        if(!empty($groupid)){
            $data['groupid'] = $groupid;
        }

        $count      = $User->where($data)->order('chinese_sum asc')->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,8);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show       = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $User->where($data)->order('chinese_sum asc')->limit($Page->firstRow.','.$Page->listRows)->select();
       // $this->assign('list',$list);// 赋值数据集
       // $this->assign('page',$show);
        $result[] = $list;
        $result[] = $show;
        ajaxReturn(0,'',$result);
    }


//==============================中演网接口
	/*投票接口*/
    public function voting(){
    	define('DB_HOST','121.41.101.8');
		define('DB_USER','nadoocomp');
		define('DB_PSWD','nadoom2db#!^');
		define('DB_NAME','zyw');
		define('DB_PORT','3306');
    	mysql_connect(DB_HOST, DB_USER, DB_PSWD, DB_PORT) or die('mysql connect fail');
		mysql_select_db(DB_NAME);
		mysql_query('set names utf8');
    	$opid = trim($_POST['opid']);
		$openid = addslashes(trim($_POST['wxopenid']));
		$ip = get_client_ip();
		/*if(!checkApiServerIp()){
		    errReturn(Errorcode::$CLIENTIP_INVALID);
		}*/
		if(preg_match("/^[a-f\d]{32}$/",$opid)){
		    //查询该openid是否投过票
		    $query = mysql_query('select id from zyw_votelog where wxopenid="'.$openid.'" and insdate="'.date('Y-m-d').'"');
		    if(mysql_num_rows($query) == 0){
		        mysql_query('update zyw_actors set votes=votes+1 where opid="'.$opid.'"');
		        if(mysql_affected_rows()){
		            $query = mysql_query('insert into zyw_votelog (actor_opid,wxopenid,ip,instime,insdate) values ("'.$opid.'","'.$openid.'","'.$ip.'",'.time().',"'.date('Y-m-d').'")');
		            //echo 'insert into zyw_votelog (actor_opid,wxopenid,ip,instime,insdate) values ("'.$opid.'","'.$openid.'","'.$ip.'",'.time().',"'.date('Y-m-d').'")';
		            if($query){
		                $query = mysql_query('select votes from zyw_actors where opid="'.$opid.'"');
		                $row = mysql_fetch_assoc($query);
		                ajaxReturn(0,'', $row);
		            }else{
		                mysql_query('update zyw_actors set votes=votes-1 where opid="'.$opid.'"');
		                ajaxReturn(1,'投票失败');
		            }  
		        }
		        
		    }else{
		        errReturn(106,'今日投票次数已达上限');
		    }
		    
		}else{
            errReturn(101,'请输入有效参数');
        }
    }
    //演员详情
    public function actorinfo(){
        $opid = trim($_POST['opid']);
        $ip = get_client_ip();
        if(preg_match("/^[a-f\d]{32}$/",$opid)){
            $actors = M('actors');
            $path = C('DOMAIN_PATH');
          //  echo $path;
            $row = $actors->query('select name,concat("'.$path.'",headimg) as headimg,concat("'.$path.'",img) as img,votes from zyw_actors where opid="'.$opid.'"');
            if(!empty($row)){
                ajaxReturn(0,'', $row);
            }    
        }else{
            errReturn(101,'请输入有效参数');
        }
    }
    //演员列表
    public function actorlist(){
         $path = C('DOMAIN_PATH');
        $sign = trim($_POST['sign']);
        list($sign, $time) = explode('.', $sign);
        if(md5('55f0fa9121e1f'.$time.'55f0fac500259') !== $sign || abs(time() - $time) > 600){
          // errReturn(102,'签名错误');
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
        $actors = M('actors');
        $data = $actors->query('select name,concat("'.$path.'",headimg) as headimg,concat("'.$path.'",img) as img,votes,groupid,sex from zyw_actors '.$where.' order by '.$orderby.' '.$ordertype.' limit '.$offset.','.$count);
        $row = $actors->query('select count(id) as c from zyw_actors'.$where);
        //echo $actors->getlastsql();
        //var_dump($row);
        $ren['total'] = intval($row[0]['c']);
        $ren['list'] = $data;
        ajaxReturn(0,'', $ren);
    }
//=====================================中演网接口END===========================================//


	/*二维码生成*/
    public function code(){
    	 vendor("phpqrcode.phpqrcode");
    	 //http://yz2500.gov.cn/TP/actor/Index/index.php?opid=3099502f8652e48cd2d15e49bb5bf67f
    		$opid = I('get.opid');
            $data = 'http://yz2500.gov.cn/TP/actor/Index/index.php?opid='.$opid;
            // 纠错级别：L、M、Q、H
            $level = 'L';
            // 点的大小：1到10,用于手机端4就可以了
            $size = 2;
            // 下面注释了把二维码图片保存到本地的代码,如果要保存图片,用$fileName替换第二个参数false
           // $path = __PUBLIC__."/images/";
            // 生成的文件名
            //$fileName = $path.$size.'.png';
            \QRcode::png($data, false, $level, $size);
            exit;
    }
    
    public function test(){

        $url = 'http://m2.nadoo.cn/p/zyw/index.php?m=Home&c=Vote&a=actorinfo';
        $data = array('opid'=>'3099502f8652e48cd2d15e49bb5bf67f','wxopenid'=>'ox9LYshHRsmsTzCOjJjmcO6N-7VA');
       $a =  $this->htcurl($url,$data);
      //var_dump($a);
    }
     public function htcurl($url,$data){
    	 $url = $url;
    	 $post_data = $data;
    	 $ch = curl_init();
    	 curl_setopt($ch, CURLOPT_URL, $url);
    	 curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    	 // post数据
    	 curl_setopt($ch, CURLOPT_POST, 1);
    	 // post的变量
    	 curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    	 $output = curl_exec($ch);
    	 curl_close($ch);
    	 //打印获得的数据
    	 print_r($output);
    }
    
}