<?php
namespace Home\Controller;
use Think\Controller;
/**
 * 用户中心
 * @author winter
 * @version 2015年11月2日16:38:31
 */
class UserController extends Controller {
    //手机绑定
    public function phonebinding(){
        $user = M('user');
        $userval = $user->find(session('userid'));
        $this->assign();
    }
}