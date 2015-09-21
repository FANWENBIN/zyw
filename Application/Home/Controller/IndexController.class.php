<?php
namespace Home\Controller;
use Think\Controller;
//首页类
class IndexController extends ComController {
    public function index(){

		$this->display();
    }


   /* public function test(){
        define('DB_HOST','121.41.101.8');
        define('DB_USER','nadoocomp');
        define('DB_PSWD','nadoom2db#!^');
        define('DB_NAME','zyw');
        define('DB_PORT','3306');
        mysql_connect(DB_HOST, DB_USER, DB_PSWD, DB_PORT) or die('mysql connect fail');
        mysql_select_db(DB_NAME);
        mysql_query('set names utf8');
        $result = mysql_query('select * from zyw_admin');
       var_dump(mysql_fetch_array($result));
    }*/
}