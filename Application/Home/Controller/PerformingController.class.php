<?php
namespace Home\Controller;
use Think\Controller;
//演艺库类
class PerformingController extends ComController {
    public function index(){
        $this->assign('sign',11);
        $this->display();
    }
    //演艺人全部按照字母排序接口数据
    public function allperforming(){
    	$type = trim(I('get.type'));
    	switch ($type) {
    		case '1':       //男演员
    			$where['sex'] = 1;
    			break;
    		case '2':		//女演员
    			$where['sex'] = 2;
    			break;
    		case '3':		//港台演员
    			$where['area'] = 1;
    			break;
    		case '4':		//内地演员
    			$where['area'] = 2;
    			break;
    		case '5':		//演艺新人
    			$where['newser'] = 1;
    			break;
    		default:

    			break;
    	}
    	$actors = M('actors');
    	$data = [];
        $condition = I('get.condition');
        if(!empty($condition)){
            $where['name|achievement'] = array('like','%'.$condition.'%'); 
        }   $where['status'] = array(array('eq',1),array('eq',2),'or');
    	foreach(range('A','Z') as $v){
			$data[$v] = $actors
						->field('id,headimg,name')
						->where("initial ='".$v."'")
						->where($where)
						->select();
			foreach($data[$v] as $key=>$val){
				$data[$v][$key]['headimg'] = './Uploads'.$val['headimg'];
			}
			if($data[$v] === false){
				ajaxReturn(101,'请求失败','');
			}
		}
		ajaxReturn(0,'',$data);
    }

    //搜索演员
    public function actorssearch(){

        $condition = I('get.condition');
        $data['name|achievement'] = array('like','%'.$condition.'%');
        $data['status'] = array(array('eq',1),array('eq',2),'or');
        $actors = M('actors');
        $actorsval = $actors->where($data)->select();
        if($actorsval === false){
            ajaxReturn(101,'请求失败','');
        }else{
            ajaxReturn(0,'',$actorsval);
        }
    }
    //明星详情
    public function actorinfo(){
        $id = I('get.id');
        //明星信息
        $actors = M('actors');
        $actorsval = $actors->where('id='.$id)->find();
        session('actors',$actorsval);
        //点击率
        $data['clickrate'] = $actorsval['clickrate']+1;
        $actors->where('id='.$id)->save($data);
        $this->assign('actorsval',$actorsval);
        //明星代表作
        $actors_production = M('actors_production');
        $production = $actors_production->where('actorsid='.$id)->select();
        $this->assign('production',$production);
        //明星动态
        $news = M('news');
        $where['status'] = 1;
        $where['keywords'] = array('like', $actorsval['name']);
        $newsval = $news->where($where)->select();
        $this->assign('newsval',$newsval);
        //推荐活动
         $active = M('active');
         $activeval = $active->where('status = 1')->order('`order` desc,instime desc,concern desc')->find();
         $this->assign('activeval',$activeval);
        $this->assign('sign',11);
        $this->display();
    }
    //提交演员资料待审核
    public function newacter(){
        //$this->checkLogin();              //验证登陆 暂时关闭
        $submit = I('post.submit');
        if(empty($submit)){
            $this->assign('sign',11);
            $this->display();
        }else{
            var_dump($submit);
            $actors = M('actors');
            $data['name']    = I('post.name'); //姓名
            $data['sex']     = I('post.sex');   // 性别
            $data['about']     = I('post.about');  //简介
            $data['headimg'] = I("post.photo1");
            $data['img']     = I("post.photo2");

            $upload = new \Think\Upload();// 实例化上传类    
            $upload->maxSize   =     3145728 ;// 设置附件上传大小    
            $upload->exts      =     array('jpg', 'png', 'jpeg');
            // 设置附件上传类型    
            $upload->savePath  =      './Uploads/user/'; // 设置附件上传目录    // 上传文件     
            $info   =   $upload->upload();    
            if(!$info) {// 上传错误提示错误信息        
                var_dump($upload->getError());    
            }else{
            // 上传成功        
               // $this->success('上传成功！');    
                echo '成功';
                var_dump($info);
            }
            $info2 = $upload->upload($_FILES["workpic[]"]);
            if(!$info2) {// 上传错误提示错误信息        
                var_dump($upload->getError());    
            }else{
            // 上传成功        
               // $this->success('上传成功！');    
                echo '成功';
                var_dump($info2);
            }
           
die();

            $a = $this->checkDump($data);
            if(!$a){
                //$this->error('添加失败，不可有空数据！',U('Gactor/index'));
            }
            $data['birthday']  = strtotime(I('post.birthday'));   //出生日期
                                //strtotime(I('post.timet'))
            $data['address']   = I('post.address');      //出生地址
            $data['constellation'] = I('post.constellation'); //星座
            $data['blood']     = I('post.blood');   //血型
            $data['height']    = I('post.height');  //身高
            $data['weight']    = I('post.weight');  //体重
            $data['talent']    = I('post.talent');  //经纪公司
            $data['promotion'] = 0;
            $data['groupid']   = 0;
            $data['newser']    = 1;
            $data['area']      = 0;

            $counts = $actors->count();
            $data['rank']    = $counts+1;   //名次
            $data['oldrank'] = $data['rank']; 



            $sur = mb_substr($data['name'],0,1,'utf-8');
            $data['opid']    = md5(date('YmdHis',time()));
            $data['instime'] = time();
            $t_hz = M('t_hz');
            $thzval = $t_hz->where("chinese='".$sur."'")->find();
            $data['chinese_sum'] = $thzval['sum'];
            $data['initial']     = getFirstCharter($data['name']);
            $data['status'] = 3;

            $model = M();                     //开启事物
            $model->startTrans();
            $Duck = true;
            
            $title = I('post.title');
            $img   = I('post.photo');
        
            $production   = M('actors_production');
            $sign = $actors->add($data);

            if($sign === false){
                    $Duck = false;
            }else{
                $id = $sign;
            }
            foreach($title as $key=>$val){
                $c['title'] = $val;
                $c['img']   = $img[$key];
                $c['actorsid'] = $id;
                $sign = $production->add($c);
                if(!$sign){
                    $Duck = false;
                    echo $production->getlastsql();die();
                }
            }
            if($Duck){
                $model->commit();
                $this->success('新增成功',U('Performing/index'));
            }else{
                $model->rollback();
                $this->error('新增失败');
            }
        }
        

    }

}
