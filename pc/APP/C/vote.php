<?php
class vote extends Controller
{
    public function index(){
        //print_r($_SERVER);
        $actors = $this->mfetchAll('select * from actors limit 8');
        $this->assign('actors',$actors);
        
        $actors = $this->mfetchAll('select * from actors order by votes desc limit 4');
        $this->assign('actors4',$actors);
        $this->display();
    }
    
}