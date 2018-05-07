<?php

namespace Quantyl\Misc\Thread ;

use \Mutex as M ;

class Mutex {

    private $id ;
    
    public function __construct() {
        $this->id = M::create() ;
        echo "Mutex created\n" ;
    }
    
    public function __destruct() {
        M::destroy($this->id) ;
        echo "Mutex destroyed\n" ;
    }
    
    public function lock() {
        return M::lock($this->id) ;
    }
    
    public function trylock() {
        return M::trylock($this->id) ;
    }
    
    public function unlock() {
        return M::unlock($this->id) ;
    }
    
}
