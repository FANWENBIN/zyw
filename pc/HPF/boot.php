<?php
$pathinfo = explode("/", trim($_SERVER['PATH_INFO'], '/'));

if(isset($pathinfo[0]) && $pathinfo[0] != ''){
    list($class) = explode('.', $pathinfo[0]);
}else{
    $class = 'index';
}

if(isset($pathinfo[1]) && $pathinfo[1] != ''){
    list($method) = explode('.', $pathinfo[1]);
}else{
    $method = 'index';
}

define('___CLASS___', $class);
define('___METHOD___', $method);
    
if(is_file(APP_PATH.'C/'.$class.'.php')){
    require_once(__DIR__.'/pdomysql.class.php');
    require_once(__DIR__.'/controller.class.php');
    require_once(APP_PATH.'C/'.$class.'.php');
    
    $obj = new $class();
    if(method_exists($obj, $method)){
        unset($pathinfo[0]);
        if(isset($pathinfo[1])) unset($pathinfo[1]);
        call_user_func_array(array($obj, $method), $pathinfo);
        exit;
    }
}else{
    if(is_file(APP_PATH.'V/'.$class.'/'.$method.'.html')){
        include_once(APP_PATH.'V/'.$class.'/'.$method.'.html');
        exit;
    }
}


exit('<h1>404 Not Found</h1>');