<?php

namespace Quantyl\Misc\Thread ;

class Locker {
    
    private $_mutex ;
    
    public function __construct(Mutex $m) {
        $this->_mutex = $m ;
        $this->_mutex->lock() ;
        echo "Locker Created\n" ;
    }
    
    public function __destruct() {
        $this->_mutex->unlock() ;
        echo "Locker Destroyed\n" ;
    }
    
}
