<?php
return array(
	//'配置项'=>'配置值'
	'DEFAULT_MODULE'     =>  'Home',  //默认模块
	'url_model'          => '0', //url模式  
	
	'session_auto_start' => false, //是否开启session
	//数据库设置
		/*'DB_TYPE'   => 'mysql', 	// 数据库类型
		'DB_HOST'   => 'localhost', // 服务器地址
		'DB_NAME'   => 'zyw',
		'DB_USER'   => 'root', 		// 用户名
		'DB_PWD'    => '',    // 密码
		'DB_PORT'   => '3306', 		     // 端口
		'DB_PREFIX' => 'zyw_', 		 // 数据库表前缀
*/
		'DB_TYPE'   => 'mysql', 	// 数据库类型
		'DB_HOST'   => '121.41.101.8', // 服务器地址
		'DB_NAME'   => 'zyw',
		'DB_USER'   => 'nadoocomp', 		// 用户名
		'DB_PWD'    => 'nadoom2db#!^',    // 密码
		'DB_PORT'   => '3306', 		     // 端口
		'DB_PREFIX' => 'zyw_', 		 // 数据库表前缀
		'SESSION_AUTO_START'    =>  true, 

	'DOMAIN_PATH'=>'http://m2.nadoo.cn/p/zyw'
	//'DOMAIN_PATH'=>'http://115.28.177.57/zyw'
);