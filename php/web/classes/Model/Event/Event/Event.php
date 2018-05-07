<?php

namespace Model\Event\Event ;

class Event {
    
    private $_name ;
    
    public function __construct($name) {
        $this->_name = $name ;
    }
    
    public function accept(\Model\Event\Listener\Listener $l, $arguments) {
        $rf_object = new \ReflectionObject($l) ;
        $rf_method = $rf_object->getMethod($this->_name) ;
        $rf_method->invokeArgs($l, $arguments) ;
    }
    
}
