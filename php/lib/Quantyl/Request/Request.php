<?php

namespace Quantyl\Request ;

use \Quantyl\Server\Server  ;

class Request {

    /* define attributes */
    
    private $_server ;
    private $_parameter ;
    private $_data ;
    
    public function __construct(Server $server, $param, $data) {
        $this->_server    = $server ;
        $this->_parameter = $param ;
        $this->_data      = $data ;
    }
    
    // Generic getters
    
    public function getParameters() {
        return $this->_parameter ;
    }
    
    public function getData() {
        return $this->_data ;
    }
    
    public function getServer() {
        return $this->_server ;
    }
    
    // specialized getters
    
    public function getServiceName() {
        
        if (isset($this->_parameter["service"])) {
            return $this->_parameter["service"] ;
        } else {
            return "Main" ;
        }
        
    }
    
    public function getReferer() {
        
        if (isset($this->_data["_referer"])) {
            return $this->_data["_referer"] ;
        }else {
            return $this->_server->getReferer() ;
        }
    }
    
    public function getClientIp() {
        return $this->_server->getClientIp() ;
    }
    
    public function getHostName() {
        return $this->_server->getServerHostname() ;
    }
    
    // Factory, à voir si on met pas ça dans deux classes spécifiques (HttpFactory et CmdLineFactory ?

    
    public static function createCmdLineRequest(Server $srv, $args) {
        return new Request($srv, $_GET, $_POST, HttpSession::getInstance()) ;
    }
}
