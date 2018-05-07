<?php

namespace Quantyl\Session ;

class Dummy extends Session implements \SessionHandlerInterface {
    
    private $_data ;
    
    public function __construct() {
        $this->_data = "" ;
    }
    
    public function close() {
        return true ;
    }
    
    public function destroy($session_id) {
        return true ;
    }
    
    public function gc($maxtime) {
        return true ;
    }
    
    public function open($save_path, $name) {
        return true ;
    }
    
    public function read($session_id) {
        return $this->_data ;
    }
    
    public function write($session_id, $session_data) {
        $this->_data = $session_data ;
        return true ;
    }
    
    public static function createHandler(\Quantyl\Server\Server $srv) {
        return new Dummy() ;
    }
    
}
