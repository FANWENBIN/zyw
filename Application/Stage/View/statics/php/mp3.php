<?php
require_once('./getid/getid3/getid3.php');

$return = array(
			'code' => 1,
			'info' => '',
			'data' => array(
						'name' => '',
						'url'  => '',
						'size' => '',
						'time' => ''
					)
			);
if($_FILES){
	$uploaddir 	= './uploads/';
	$t 	 		= explode(".", $_FILES['Filedata']['name']);
	$fileName 	= time().rand(1000,9999).'.'.array_pop($t); 
	$tmpName 	= $_FILES['Filedata']['tmp_name'];
	$fileSize 	= $_FILES['Filedata']['size'];
	$error 		= $_FILES['Filedata']['error'];

	if(0 == $error){
		$uploadfile = $uploaddir . $fileName;
		$uploadfile = iconv('utf-8', 'gb2312', $uploadfile);
		move_uploaded_file($tmpName, $uploadfile);
		$return['data']['name'] = $_FILES['Filedata']['name'];
		$return['data']['url'] 	= $fileName;
		$return['data']['size']	= $fileSize;
		$getID3 = new getID3;
		$t = $getID3->analyze('./uploads/'.$fileName);
		$return['data']['time'] = $t['playtime_seconds'];
	}else {
		$return['code'] = 0;
		$return['info'] = '服务器出错';
	}

	echo json_encode($return);
}else {
	echo json_encode($return);
}