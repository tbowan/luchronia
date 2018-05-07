<?php

namespace Model\Event ;

class Listening extends \Quantyl\Dao\BddObject {
    
    // Parser
    
    public static function FromBddValue($name, $value) {
        switch($name) {
            default:
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            default:
                return $value ;
        }
    }
    
    // Get some sets
    
    public static function GetFromEvent($name) {
        return static::getResult(""
                . " select *"
                . " from `" . self::getTableName() . "`"
                . " where"
                . "  event = :event",
                array("event" => $name)
                ) ;
    }
    

    // Triger an event
    
    public static function __callStatic($name, $arguments) {
        $event = self::_eventFactory($name) ;
        foreach (self::GetFromEvent($name) as $listening) {
            $listener = self::_listenerFactory($listening->listener) ;
            $event->accept($listener, $arguments) ;
        }
    }
    
    private static function _eventFactory($event) {
        $base = "\\Model\\Event\\Event\\" ;
        $classname = $base . $event ;
        $default   = $base . "Event" ;
        
        if (class_exists($classname)) {
            return new $classname($event) ;
        } else {
            return new $default($event) ;
        }
    }
    
    private static function _listenerFactory($listener) {
        $base = "\\Model\\Event\\Listener\\" ;
        $classname = $base . $listener ;
        $default   = $base . "Listener" ;
        
        if (class_exists($classname)) {
            return new $classname($listener) ;
        } else {
            return new $default($listener) ;
        }
    }
    
}
