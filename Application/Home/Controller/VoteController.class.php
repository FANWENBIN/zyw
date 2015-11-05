<?php
// 本类由系统自动生成，仅供测试用途
namespace Home\Controller;
use Think\Controller;
class VoteController extends ComController {
    public function index(){
        $actors = M('actors');
        //好演员评选
        $where['status']=1;
        $actorsval = $actors->where($where)->order(array('votes'=>'desc','chinese_sum'=>'asc'))->limit('0,8')->select();
        $this->assign('actors',$actorsval);
        //形象指数
        $where['groupid'] = 1;
        $red = $actors->where($where)->order(array('votes'=>'desc','chinese_sum'=>'asc'))->limit('0,2')->select();
        $where['groupid'] = 2;
        $manblue = $actors->where($where)->where('sex = 1')->order(array('votes'=>'desc','chinese_sum'=>'asc'))->limit('0,2')->select();
        $woblue  = $actors->where($where)->where('sex = 2')->order(array('votes'=>'desc','chinese_sum'=>'asc'))->limit('0,2')->select();
        $where['groupid'] = 3;
        $green = $actors->where($where)->order(array('votes'=>'desc','chinese_sum'=>'asc'))->limit('0,2')->select();
        $actorsvalue = $red;
 
        foreach ($manblue as $key => $value) {
            array_push($actorsvalue, $value);
        }
        foreach ($woblue as $key => $value) {
            array_push($actorsvalue, $value);
        }
        foreach ($green as $key => $value) {
            array_push($actorsvalue, $value);
        }

        $this->assign('list',$actorsvalue);
  
        $recommend = M('recommend');
        //当代艺术家
        $artists   = $recommend->where('type=1')->select();
        $this->assign('artists',$artists);
        //导演
        $director  = $recommend->where('type=2')->select();
        $this->assign('director',$director);
        //制作人
        $producer  = $recommend->where('type=3')->select();
        $this->assign('producer',$producer);
        //编剧
        $scriptwriter = $recommend->where('type=4')->select();
        $this->assign('scriptwriter',$scriptwriter);
    
        $this->assign('sign',5);
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
        $data['status']=1;
        $count      = $actors->where($data)->order('chinese_sum asc')->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,8);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show       = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $actors->where($data)->order('chinese_sum asc')->limit($Page->firstRow.','.$Page->listRows)->select();
       // $this->assign('list',$list);// 赋值数据集
       // $this->assign('page',$show);
        $result[] = $list;
        $result[] = $show;
        ajaxReturn(0,'',$result);
    }


//==============================中演网对外接口Start=======================//
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
		            //echo 'insert into zyw_votelog (actor_opid, ,ip,instime,insdate) values ("'.$opid.'","'.$openid.'","'.$ip.'",'.time().',"'.date('Y-m-d').'")';
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
    /*改版投票接口
    每人可投36票，分3组男女。各六票，不可重复
    author：winter
    date：2015年10月30日14:27:33
    */
    public function newvoting(){
        $votelog = M('votelog');
        $actors  = M('actors');
        $model = M();
        //接受参数
        $opid = I('post.opid','','trim');
        $openid = trim(I('post.wxopenid','','addslashes'));
        $ip = get_client_ip();
        if(preg_match("/^[a-f\d]{32}$/",$opid)){
            $actorsval = $actors->where("opid='".$opid."'")->find();
            if(!$actorsval){
                errReturn(102,'明星查找失败，不可对其投票');
            }
            $condition['wxopenid'] = $openid;
            $condition['insdate'] = date('Y-m-d',time());
            $sum = $votelog->where($condition)->count();
            if($sum < 36){
                $condition['groupid'] = $actorsval['groupid'];
                $sum = $votelog->where($condition)->count();
                if($sum < 12){
                    $condition['sexid'] = $actorsval['sex'];
                    $sum = $votelog->where($condition)->count();
                    if($sum < 6){
                        $condition['actor_opid'] = $opid;
                        $sum = $votelog->where($condition)->count();
                        if($sum < 1){
                            $model->startTrans();
                            $sign = true;
                            $data['actor_opid'] = $opid;
                            $data['wxopenid']   = $openid;
                            $data['ip']         = $ip;
                            $data['instime']    = time();
                            $data['insdate']    = date('Y-m-d',time());
                            $data['groupid']    = $actorsval['groupid'];
                            $data['sexid']      = $actorsval['sex'];
                            $vosign = $votelog->add($data);
                            if(!$vosign){
                                $sign = false;
                            }
                            
                            $tick['votes'] = $actorsval['votes']+1;
                            $acsign = $actors->where("opid='".$opid."'")->save($tick);
                           
                            if(!$acsign){
                                $sign = false;
                            }
                            if($sign){
                                $model->commit();
                                $result = $actors->field('votes')->where("opid='".$opid."'")->find();
                                ajaxReturn(0,'',$result);
                            }else{
                                $model->rollback();
                                errReturn(1,'投票失败');
                            }
                        }else{
                            errReturn(201,'今日已给该演员投过票');
                        }
                    }else{
                        if($actorsval['sex'] == 1){
                            errReturn(108,'今日给该组男演员投票已达上限');
                        }else{
                            errReturn(109,'今日给该组女演员投票已达上限');
                        }
                        
                    }
                }else{
                    errReturn(107,'今日给该组投票已达上限');
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
            $path = C('DOMAIN_PATH').'/Uploads';
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
         $path = C('DOMAIN_PATH').'/Uploads';
        $sign = trim($_POST['sign']);
        list($sign, $time) = explode('.', $sign);
        if(md5('55f0fa9121e1f'.$time.'55f0fac500259') !== $sign || abs(time() - $time) > 600){
           errReturn(102,'签名错误');
        }
        $offset = isset($_POST['offset']) ? max(0,intval($_POST['offset'])) : 0;
        $count = isset($_POST['count']) ? min(1000,max(1,intval($_POST['count']))) : 10;
        $orderby = isset($_POST['orderby']) ? trim($_POST['orderby']) : '';
        $ordertype = isset($_POST['ordertype']) && $_POST['ordertype'] == 'desc' ? 'desc' : 'asc';
        $groupid = isset($_POST['groupid']) ? intval($_POST['groupid']) : 0;
        $sex = isset($_POST['sex']) ? intval($_POST['sex']) : 0;

        if(!in_array($orderby, array('name','votes',''))){
            //ajaxReturn(1,'orderby 参数不合法');
        }

        if(!$orderby) $orderby = 'id';

        $where = array();
        if($groupid > 0){
            $where[] = 'groupid='.$groupid;
        }
        if($sex > 0){
            $where[] = 'sex='.$sex;
        }
        $where[]= 'status=1';
        if(!empty($where)){
            $where = ' where '.implode(' and ', $where);
        }else{
            $where = '';
        }

        $actors = M('actors');
        $data = $actors->query('select name,concat("'.$path.'",headimg) as headimg,concat("'.$path.'",img) as img,votes,groupid,sex from zyw_actors '.$where.' order by '.$orderby.' '.$ordertype.' limit '.$offset.','.$count);
        $row = $actors->query('select count(id) as c from zyw_actors'.$where);
        //echo $actors->getlastsql();
        //var_dump($data);
        $ren['total'] = intval($row[0]['c']);
        $ren['list'] = $data;
        ajaxReturn(0,'',$ren);
    }
//=====================================中演网接口END===========================================//


	/*二维码生成*/
    public function code(){
        ob_end_clean();
    	 vendor("phpqrcode.phpqrcode");
    		$opid = I('get.opid');
            //$data = 'http://yz2500.gov.cn/TP/actor/Index/index.php?opid='.$opid;
             $data = 'http://www.zmyzr.gov.cn/zyw_vote/Index/index.php?opid='.$opid;
            
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
    //投票页，按照姓氏排名
    public function redgroup(){
        
        $url = C('DOMAIN_PATH')."/index.php?m=Home&c=Vote&a=code";
        $sex = I('get.sex');
        $actors = M('actors');
        if($sex){
            $where['sex'] = $sex;
        }
        $where['groupid'] = 1;
        $where['status'] = 1;
        $actorsval = $actors->where($where)->order('chinese_sum asc')->select();
        foreach($actorsval as $key=>$val){
            $actorsval[$key]['lifting'] = $val['oldrank']-$val['rank'];
            $actorsval[$key]['codeimg'] = $url.="&opid=".$val['opid'];
        }
        
        if($actorsval === false){
            ajaxReturn(1,'系统错误','');
        }else{
            ajaxReturn(0,'',$actorsval);
        }

    }
    public function bluegroup(){
        $url = C('DOMAIN_PATH')."/index.php?m=Home&c=Vote&a=code";
        $sex = I('get.sex');
        $actors = M('actors');
        if($sex){
            $where['sex'] = $sex;
        }
        $where['groupid'] = 2;
        $where['status'] = 1;
        $actorsval = $actors->where($where)->order('chinese_sum asc')->select();
        foreach($actorsval as $key=>$val){
            $actorsval[$key]['lifting'] = $val['oldrank']-$val['rank'];
            $actorsval[$key]['codeimg'] = $url.="&opid=".$val['opid'];
        }
        if($actorsval === false){
            ajaxReturn(1,'系统错误','');
        }else{
            ajaxReturn(0,'',$actorsval);
        }
    }
    public function greengroup(){
        $url = C('DOMAIN_PATH')."/index.php?m=Home&c=Vote&a=code";
        $sex = I('get.sex');
        $actors = M('actors');
        if($sex){
            $where['sex'] = $sex; 
        }
        $where['groupid'] = 3;
        $where['status'] = 1;
        $actorsval = $actors->where($where)->order('chinese_sum asc')->select();
        foreach($actorsval as $key=>$val){
            $actorsval[$key]['lifting'] = $val['oldrank']-$val['rank'];
            $actorsval[$key]['codeimg'] = $url.="&opid=".$val['opid'];
        }
        if($actorsval === false){
            ajaxReturn(1,'系统错误','');
        }else{
            ajaxReturn(0,'',$actorsval);
        }
    }
    //=================END
   //候选演员和获奖者查询接口
    public function wininter(){
        $actors = M('actors');
        $condition = trim(I('get.condition'));

        if($condition != 6 && $condition != 36){
            ajaxReturn(104,'参数错误','');
        }
        $where['promotion'] = $condition;
        $sign = $actors->where($where)->count();
        if($sign < 1){ajaxReturn(102,'未产生'.$condition.'强','');}
        if($condition == 36){
            $groupid = trim(I('get.groupid'));
            $sex     = trim(I('get.sex'));
            if($groupid < 0 || $groupid > 3){
                ajaxReturn(104,'参数错误','');
            }
            $where['groupid'] = $groupid;
            $where['sex']     = $sex;
        }

        //入围演员
        $cutactors = $actors->field('headimg,votes,id,name,groupid')->where($where)->order(array('groupid'=>'asc','votes'=>'desc','chinese_sum'=>'asc'))->limit(0,$condition)->select();
       if($cutactors === false){
            ajaxReturn(101,'请求失败','');
       }else{
        foreach ($cutactors as $key => $value) {
            $cutactors[$key]['groupid'] = ($value['groupid'] == 1) ? '红组' : (($value['groupid'] == 2) ? '蓝组' : '绿组');
            $cutactors[$key]['headimg'] = './Uploads'.$value['headimg'];
        }
            if(!$cutactors){$cutactors= array();}
            ajaxReturn(0,'',$cutactors);
       }
       
    }

    //无标题的页面

    public function votenonav(){
        $actors = M('actors');
        //好演员评选
        $where['status']=1;
        $actorsval = $actors->where($where)->order(array('votes'=>'desc','chinese_sum'=>'asc'))->limit('0,8')->select();
        $this->assign('actors',$actorsval);
        //形象指数
        $where['groupid'] = 1;
        $red = $actors->where($where)->order(array('votes'=>'desc','chinese_sum'=>'asc'))->limit('0,2')->select();
        $where['groupid'] = 2;
        $manblue = $actors->where($where)->where('sex = 1')->order(array('votes'=>'desc','chinese_sum'=>'asc'))->limit('0,2')->select();
        $woblue  = $actors->where($where)->where('sex = 2')->order(array('votes'=>'desc','chinese_sum'=>'asc'))->limit('0,2')->select();
        $where['groupid'] = 3;
        $green = $actors->where($where)->order(array('votes'=>'desc','chinese_sum'=>'asc'))->limit('0,2')->select();
        $actorsvalue = $red;

        foreach ($manblue as $key => $value) {
            array_push($actorsvalue, $value);
        }
        foreach ($woblue as $key => $value) {
            array_push($actorsvalue, $value);
        }
        foreach ($green as $key => $value) {
            array_push($actorsvalue, $value);
        }

        $this->assign('list',$actorsvalue);
  
        $recommend = M('recommend');
        //当代艺术家
        $artists   = $recommend->where('type=1')->select();
        $this->assign('artists',$artists);
        //导演
        $director  = $recommend->where('type=2')->select();
        $this->assign('director',$director);
        //制作人
        $producer  = $recommend->where('type=3')->select();
        $this->assign('producer',$producer);
        //编剧
        $scriptwriter = $recommend->where('type=4')->select();
        $this->assign('scriptwriter',$scriptwriter);
        $this->display();
    }
}