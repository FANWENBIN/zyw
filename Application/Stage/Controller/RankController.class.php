<?php
namespace Stage\Controller;
use Think\Controller;
//每日更新名次
class RankController extends ComController {

    public function index(){
          $actors = M('actors');  
          $actorsval = $actors->order('votes desc')->select();
          $actors->startTrans();
		  $fall = true;
          foreach($actorsval as $key=>$val){
          	$data['oldrank'] = $val['rank'];
          	$data['rank']    = $key+1;
          	$id = $val['id'];
          	$rank = $key+1;
          	if($rank != $data['rank'] || $val['rank'] != $val['oldrank']){  //如果没有改变就不做改变
				$sign = $actors->where('id='.$id)->save($data);
          	}
          	if(!$sign){
          		$actors->rollback();
          		$fall = false;
          	}
          }
         if($fall){
         	$actors->commit();
         	echo '修改成功';
         }else{
         	echo '修改失败';
         }
         
    }

    
}