<?php

namespace Quantyl\Session ;

abstract class Session {

    public abstract static function createHandler(\Quantyl\Server\Server $sfv) ;
    
    public function _setLong() { }
    
    public function _checkLong() { }
    
    public final static function initSessions(\Quantyl\Server\Server $srv) {
        
        $cfg   = $srv->getConfig() ;
        $class = $cfg["session.class"] ;
        if ($class != null && $class != "native") {
            $rfClass = new \ReflectionClass($cfg["session.class"]) ;
            $rfMethod = $rfClass->getMethod("createHandler") ;
            $handler = $rfMethod->invoke(null, $srv) ;
            session_set_save_handler($handler) ;
            
            self::$_handler = $handler ;
        } else {
            self::$_handler = null ;
        }
        
        session_start() ;
        
        self::checkLong() ;
        
    }
    
    private static $_handler ;
    
    public static function getHandler() {
        return self::$_handler ;
    }
    
    public static function setLong() {
        return (self::$_handler === null ? true : self::$_handler->_setLong()) ;
    }
    
    public static function checkLong() {
        return (self::$_handler === null ? true : self::$_handler->_checkLong()) ;
    }
    
}
