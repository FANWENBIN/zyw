<?php
/*
Uploadify
Copyright (c) 2012 Reactive Apps, Ronnie Garcia
Released under the MIT License <http://www.opensource.org/licenses/mit-license.php> 
*/

// Define a destination
$targetFolder = 'zyw/Uploads/fans'; // Relative to the root

//$verifyToken = md5('unique_salt'.$_POST['timestamp']);

//if (!empty($_FILES) && $_POST['token'] == $verifyToken) {
if (!empty($_FILES)) {
	$tempFile = $_FILES['Filedata']['tmp_name'];
	$targetPath = $_SERVER['DOCUMENT_ROOT'] . $targetFolder;
	if(!is_dir($targetPath)){
            mkdir($targetPath,0777,true);
    }
	$targetFile = rtrim($targetPath,'/') . '/' . $_FILES['Filedata']['name'];
	//var_dump($targetFile);die();
	// Validate the file type
	$fileTypes = array('jpg','jpeg','gif','png'); // File extensions
	$fileParts = pathinfo($_FILES['Filedata']['name']);
	
	if (in_array($fileParts['extension'],$fileTypes)) {
		var_dump($tempFile);
		move_uploaded_file($tempFile,$targetFile);
		$data['status'] = 0;
		$data['msg']  = '';
		$data['data'] = array('targetFile'=>$targetFile);
		exit(json_encode($data,JSON_UNESCAPED_UNICODE));
	} else {
		$data['status'] = 101;
		$data['msg']  = '上传失败';
		$data['data'] = '';
		exit(json_encode($data,JSON_UNESCAPED_UNICODE));
		
	}
}
?>