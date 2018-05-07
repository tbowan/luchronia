<?php

namespace Quantyl\Exception\Http ;

abstract class HttpException extends \Exception {
    
    public function __construct($message, $code, $previous) {
        parent::__construct($message, $code, $previous);
    }
    
    private function getClass() {
        $classname = get_class($this) ;
        $res = preg_match("/.*\\\\([^\\\\]*)/", $classname, $matches) ;
        if (! $res || count($matches) < 2) {
            return $classname ;
        } else {
            return $matches[1] ;
        }
    }
    
    public function getName() {
        $classname = $this->getClass() ;
        $key = "Exception-$classname-name" ;
        return \I18n::translate($key, array()) ;
    }
    
    public function getDescription() {
        $classname = $this->getClass() ;
        $key = "Exception-$classname-description" ;
        return \I18n::translate($key, array()) ;
    }
    
    public static function FromException(\Exception $e) {
        return self::FromCode($e->getCode(), $e->getMessage(), $e->getPrevious()) ;
    }
    
    public static function FromCode($code, $message= null, $previous = null) {
        switch($code) {
            case 400 : return new ClientBadRequest      ($message, $previous) ;
            case 401 : return new ClientUnauthorized    ($message, $previous) ;
            case 402 : return new ClientPaymentRequired ($message, $previous) ;
            case 403 : return new ClientForbidden       ($message, $previous) ;
            case 404 : return new ClientNotFound        ($message, $previous) ;
            default  : return new ServerInternalError   ($message, $previous) ;
        }
    }
    
}
