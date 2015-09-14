<?php
class PDOMysql extends PDO
{
    
    protected $PDOStatement = null;
    
    //本次执行的sql语句列表
    protected $lastSql   = array();
    
    protected $error = '';
    
    public function __construct(){
        try{
            parent::__construct("mysql:host=".DB_HOST.";port=".DB_PORT.";dbname=".DB_NAME.';charset=utf8',DB_USER,DB_PSWD, array(
    	    	PDO::MYSQL_ATTR_INIT_COMMAND => "set names utf8",
    	    	PDO::ATTR_EMULATE_PREPARES => false,
    	    	PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    	    	PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true
    	    ));
            
        }catch(PDOException $e) {
    	    $this->error = $e->getMessage();
            return false;
    	}
    }
    
   
    
    public function __call($func,$params){
        if($this->PDOStatement && method_exists($this->PDOStatement, $func)){
            call_user_func_array(array($this->PDOStatement, $func), $params);
        }else{
            throw new PDOException("调用的方法不存在：".$func);
        }
        
    }
    
    
    
    public function mfetch($sql, $bindValue = array()){
        $this->prepare($sql);
        $this->execute($bindValue);
        $res = $this->PDOStatement->fetch(PDO::FETCH_ASSOC);
        return $res !== false ? $res : array();
    }
    public function mfetchAll($sql, $bindValue = array()){
        $this->prepare($sql);
        $this->execute($bindValue);
        return $this->PDOStatement->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
    public function rowCount(){
        return $this->PDOStatement->rowCount();
    }

    public function fetch_assoc(){
        return $this->PDOStatement->fetch(PDO::FETCH_ASSOC);
    }
    
    public function selectAll($sql){
        $this->query($sql);
        return $this->PDOStatement->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function selectOne($sql){
        $this->query($sql);
        return $this->fetch_assoc();
    }
    
    public function insertArray($table, $dataArray){
        $fields = array_keys($dataArray);
        $sql = 'INSERT INTO '.$table.' ('.implode(',', $fields).') VALUES (:'.implode(',:', $fields).')';
        $this->prepare($sql);
        foreach($dataArray as $field => $value){
            $this->bindValue(':'.$field, $value);
        }
        $this->execute();
        return $this->lastInsertId();
    }
    
    public function insertAll($table, $dataArray){
        $fields = $sqlArr = array();
        foreach($dataArray as $array){
            $values = array();
            foreach($array as  $field => $value){
                if(empty($sqlArr)){
                    $fields[] = '`'.$field.'`';
                }
                
                $values[] = '"'.$this->sql_escape_string($value).'"';
            }
            
            $sqlArr[] = '('.implode(',', $values).')';
        }
        
        $sql = 'INSERT INTO '.$table.' ('.implode(',', $fields).') VALUES '.implode(',', $sqlArr);
        $this->exec($sql);
        return $this->lastInsertId()-1+count($dataArray);
    }
    
    public function lastSql(){
        return $this->lastSql;
    }
    
    /**
     * Mysql::update()
     * 
     * @param mixed $table
     * @param mixed $data_arrayORstring
     * @param mixed $condition
     * @return updated rows 更新的行数
     * 示例
     * public function deletes(){
        $where = array(
            'id' => '2',
            '*id' => array('or',4),
            '***id' => array('or', 'exp'=>array('>',25)),
            '**id' => array('exp'=>array('>',25)),
        );
        return $this->delete('test', $where);
       }
     */
    public function update($table, $data_arrayORstring, $condition){
        $values = array();
        if(is_array($data_arrayORstring)){
            $tmp = array();
            foreach($data_arrayORstring as $field => $value){
                 $tmp[] = '`'.$field.'`=:'.$field;
                 $values[':'.$field] = $value;
            }
            $data_arrayORstring = implode(',', $tmp);
        }
        if(is_array($condition)){
            $tmp = array();
            foreach($condition as $field => $value){
                $tmpField = $field;
                $field = ltrim($field, '*');
                $tmpField = str_replace('*','_',$tmpField);
                if(!is_array($value)){
                    $tmp[] = '`'.$field.'`=:'.$tmpField;
                    $values[':'.$tmpField] = $value;
                }else{
                    if(isset($value[0]) && strtolower($value[0]) == 'or'){
                        $tmpStr = '(('.implode(' AND ', $tmp).') OR `'.$field.'`';
                        if(isset($value[1])){
                            $tmpStr .= '=:'.$tmpField;
                            $values[':'.$tmpField] = $value[1];
                        }elseif(isset($value['exp'])){
                            $tmpStr .= $value['exp'][0].':'.$tmpField;
                            $values[':'.$tmpField] = $value['exp'][1];
                        }else{
                            throw new PDOException('表达式参数不合法');
                            
                        }
                        $tmpStr .= ')';
                        $tmp = array($tmpStr);
                    }elseif(isset($value['exp'])){
                        $tmp[] = '`'.$field.'`'.$value['exp'][0].':'.$tmpField;
                        $values[':'.$tmpField] = $value['exp'][1];
                    }
                }
            }
            $condition = implode(' AND ', $tmp);
        }
        $sql = 'UPDATE '.$table.' SET '.$data_arrayORstring.' WHERE ('.$condition.')';//print_r($values);exit($sql);
        $this->prepare($sql);
        $this->execute($values);
        return $this->rowCount();
    }
    
    /**
     * Mysql::delete()
     * 
     * @param mixed $table
     * @param mixed $condition
     * @return deleted rows 删除的行数
     * 
     * 示例
     * public function deletes(){
        $where = array(
            'id' => '2',
            '*id' => array('or',4),
            '***id' => array('or', 'exp'=>array('>',25)),
            '**id' => array('exp'=>array('>',25)),
        );
        return $this->delete('test', $where);
       }
     */
    public function delete($table, $condition){
        $values = array();
        if(is_array($condition)){
            $tmp = array();
            foreach($condition as $field => $value){
                $tmpField = $field;
                $field = ltrim($field, '*');
                $tmpField = str_replace('*','_',$tmpField);
                if(!is_array($value)){
                    $tmp[] = '`'.$field.'`=:'.$tmpField;
                    $values[':'.$tmpField] = $value;
                }else{
                    if(isset($value[0]) && strtolower($value[0]) == 'or'){
                        $tmpStr = '(('.implode(' AND ', $tmp).') OR `'.$field.'`';
                        if(isset($value[1])){
                            $tmpStr .= '=:'.$tmpField;
                            $values[':'.$tmpField] = $value[1];
                        }elseif(isset($value['exp'])){
                            $tmpStr .= $value['exp'][0].':'.$tmpField;
                            $values[':'.$tmpField] = $value['exp'][1];
                        }else{
                            throw new PDOException('表达式参数不合法');
                            
                        }
                        $tmpStr .= ')';
                        $tmp = array($tmpStr);
                    }elseif(isset($value['exp'])){
                        $tmp[] = '`'.$field.'`'.$value['exp'][0].':'.$tmpField;
                        $values[':'.$tmpField] = $value['exp'][1];
                    }
                }
                
            }
            $condition = implode(' AND ', $tmp);
        }
        $sql = 'DELETE FROM '.$table.' WHERE ('.$condition.')';
        $this->prepare($sql);
        $this->execute($values);
        return $this->rowCount();
    }
    
    /**
     * Mysql::select()
     * 
     * @param mixed $table 表名
     * @param mixed $fieldsString 查询的字段列表，多个用英文逗号分隔
     * @param mixed $condition 条件
     * @param string $others 拼接在where后面的其他语句
     * @param bool $all 是否查询全部，如果是查询出来的是二维数组，否则是一维数组
     * @return selected data 查询的数据
     * 
     * 示例
     * public function sele(){
        $where = array(
            'id' => '2',
            '*id' => array('or',4),
            '***id' => array('or', 'exp'=>array('>',25)),
            '**id' => array('exp'=>array('>',25)),
        );
        return $this->select('test', '*', $where,' or id < 10 order by id desc limit 3',false);
       }
     */
    public function select($table,$fieldsString,$condition, $others = '',$all = true){
        $values = array();
        if(is_array($condition)){
            $tmp = array();
            foreach($condition as $field => $value){
                $tmpField = $field;
                $field = ltrim($field, '*');
                $tmpField = str_replace('*','_',$tmpField);
                if(!is_array($value)){
                    $tmp[] = '`'.$field.'`=:'.$tmpField;
                    $values[':'.$tmpField] = $value;
                }else{
                    if(isset($value[0]) && strtolower($value[0]) == 'or'){
                        $tmpStr = '(('.implode(' AND ', $tmp).') OR `'.$field.'`';
                        if(isset($value[1])){
                            $tmpStr .= '=:'.$tmpField;
                            $values[':'.$tmpField] = $value[1];
                        }elseif(isset($value['exp'])){
                            $tmpStr .= $value['exp'][0].':'.$tmpField;
                            $values[':'.$tmpField] = $value['exp'][1];
                        }else{
                            throw new PDOException('表达式参数不合法');
                            
                        }
                        $tmpStr .= ')';
                        $tmp = array($tmpStr);
                    }elseif(isset($value['exp'])){
                        $tmp[] = '`'.$field.'`'.$value['exp'][0].':'.$tmpField;
                        $values[':'.$tmpField] = $value['exp'][1];
                    }
                }
                
            }
            $condition = implode(' AND ', $tmp);
        }
        $sql = 'SELECT '.$fieldsString.' FROM '.$table.' WHERE ('.$condition.') '.$others;
        if($all){
            return $this->mfetchAll($sql, $values);
        }
        return $this->mfetch($sql, $values);
    }
    
    public function sql_escape_string($string){
        return addslashes($string);
    }
    
    public function sql_escape_array($array){
        foreach($array as &$val){
            if(is_array($val)){
                $val = $this->sql_escape_array($val);
            }else{
                $val = $this->sql_escape_string($val);
            }
            
        }
        return $array;
    }
    
    
    
    
    public function pageSelect($baseSql, $page = 1, $pageSize = 10, $orderBy = ''){
        if($orderBy != ''){
            $baseSql .= ' ORDER BY '.$orderBy;
        }
        $sql = $baseSql . ' LIMIT '.($page -1)*$pageSize.','.$pageSize;
        return $this->selectAll($sql);
    }
    
    public function logErrSql($errmsg, $sql){
        $file = __DIR__.'/errsql.log';
        if(is_writable($file)){
            $fp = fopen($file,'a');
            fwrite($fp, date('Y-m-d H:i:s')."\n");
            fwrite($fp, $errmsg."\n");
            fwrite($fp, $sql."\n\n");
            fclose($fp);
        }
    }
    
    //事务
    public function startTransaction(){
        if(!$this->inTransaction()){
            $this->beginTransaction();
        }
        
    }
    public function rollbackTransaction(){
        if($this->inTransaction()){
            $this->rollback();
        }
    }
    public function commitTransaction(){
        if($this->inTransaction()){
            $this->commit();
        }
        
    }
    
    public function getError(){
        return $this->error;
    }
    
    public function prepare($sql, $options = NULL){
        $this->lastSql[] = $sql;
        $this->PDOStatement = parent::prepare($sql);
    }
    
    protected function execute($params = array()){
        if(!empty($params)){
            $this->PDOStatement->execute($params);
        }else{
            $this->PDOStatement->execute();
        }
        
    }
    
    public function exec($sql){
        $this->lastSql[] = $sql;
        $row = parent::exec($sql);
        if(preg_match("/^\s*(INSERT\s+INTO|REPLACE\s+INTO)\s+/i", $sql)) {
            return $this->lastInsertId();
        }else{
            return $row;
        }
    }
    
    public function query($sql){
        $this->lastSql[] = $sql;
        $this->PDOStatement = parent::query($sql);
    }
    public function __destruct(){
        if($this->PDOStatement){
            $this->PDOStatement = null;
        }
    }
}