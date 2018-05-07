<?php

namespace Quantyl\Misc\Thread ;

class Counter {
    
    private $_value ;
    private $_mutex ;
    
    public function __construct() {
        $this->_value = 0 ;
        $this->_mutex = new Mutex() ;
    }
    
    public function setValue($value) {
        $locker = new Locker($this->_mutex) ;
        $this->_value = $value;
    } 
    
    public function getValue() {
        $locker = new Locker($this->_mutex) ;
        return $this->_value ;
    }
    
    public function increment($step = 1) {
        $locker = new Locker($this->_mutex) ;
        $this->_value += $step ;
    }
    
    public function __destruct() {
        $locker = new Locker($this->_mutex) ;
    }
}
