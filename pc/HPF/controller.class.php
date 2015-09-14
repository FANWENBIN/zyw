<?php
abstract class Controller extends PDOMysql
{
    private $assignVar = array();
    
    protected function assign($name, $value){
        $this->assignVar[$name] = $value;
    }
    protected function display($tpl = ''){
        
        if(strpos($tpl, '/') === false){
            if($tpl == ''){
                $tpl = get_class($this).'/'.___METHOD___;
            }else{
                $tpl = get_class($this).'/'.$tpl;
            }
        }
        if(is_file(APP_PATH.'V/'.$tpl.'.html')){
            extract($this->assignVar);
            include_once(APP_PATH.'V/'.$tpl.'.html');
        }else{
            echo 'tpl not exists : '.$tpl;
        }
        
    }
}