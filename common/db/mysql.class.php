<?php
class Mysql
{
    protected $_linkID = null;
    protected $PDOStatement = null;
    
    //本次执行的sql语句列表
    protected $queryStr   = array();
    // 最后插入ID
    protected $lastInsID  = null;
    // 返回或者影响记录数
    protected $numRows    = 0;
    // 事务指令数
    protected $transactionsLevel = 0;
    
    protected $error = '';
    
    public function __construct(){
        if($this->_linkID === null){
            try{
                $this->_linkID = new PDO("mysql:host=".DB_HOST.";port=".DB_PORT.";dbname=".DB_NAME,DB_USER,DB_PSWD, array(
        	    	PDO::MYSQL_ATTR_INIT_COMMAND => "set names utf8;charset=utf8",
        	    	PDO::ATTR_EMULATE_PREPARES => false,
        	    	PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        	    	PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true
        	    ));
            }catch(PDOException $e) {
        	    $this->error = $e->getMessage();
                return false;
        	}
        }
        return true;
    }
    
    //事务
    public function startTransaction(){
        $this->transactionsLevel++;
        if($this->transactionsLevel == 1){
            $this->_linkID->beginTransaction();
        }
        
    }
    public function rollbackTransaction(){
        $this->transactionsLevel--;
        if($this->transactionsLevel == 0){
            $this->_linkID->rollback();
        }
    }
    public function commitTransaction(){
        $this->transactionsLevel--;
        if($this->transactionsLevel == 0){
            $this->_linkID->commit();
        }
        
    }
    
    public function getError(){
        return $this->error;
    }
    public function __destruct(){
        if($this->PDOStatement){
            $this->PDOStatement = null;
        }
        $this->_linkID = null;
    }
}