<?php
class Errorcode
{
    static $OPID_ERROR = 101;
    static $WXOPENID_DUPLICAT = 102;
    static $CLIENTIP_INVALID = 103;
    static $INVALID_SIGN = 104;
    static $PARAMS_ERROR = 105;
    static $DAYS_VOTE_OUT_LIMIT = 106;
    
    
    static $codeMap = array(
        0   => '请求成功',
        
        101 => 'opid无效',
        102 => '微信openid重复',
        103 => '客户端IP地址非法',
        104 => '签名错误',
        105 => '参数有误',
        106 => '今日投票次数已达上限'
    );
    
    public function getErrorMsg($code){
        return isset(self::$codeMap[$code]) ? self::$codeMap[$code] : '';
    }
}