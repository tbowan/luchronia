<?php

namespace Quantyl\Server ;

use \Quantyl\Session\ArraySession ;
use \Quantyl\Configuration\Configuration ;
use \Quantyl\Request\Request ;


class CmdLineServer extends Server {
    
    private $_argv ;
    
    public function __construct(Configuration $config, $argv) {
        parent::__construct($config);
        $this->_argv = $argv ;
    }
    
    private function parseArgs() {
        $params = array() ;
        for ($i = 0; $i<count($this->_argv) - 1; $i++) {
            $res = preg_match("/^-(.*)/", $this->_argv[$i], $matches) ;
            if ($res == 1 && count($matches) == 2) {
                $params[$matches[1]] = $this->_argv[$i+1] ;
                $i++ ;
            }
        }
        return $params ;
    }
    
    public function createRequest() {
        return new Request($this, $this->parseArgs(), array()) ;
    }
    
    public function getReferer() {
        return "" ;
    }
    
    public function getServerHostname() {
        $res = gethostname() ;
        if (! $res) {
            $res = php_uname("n");
        }
        return $res;
    }

    public function getClientIp() {
        return "command line" ;
    }
    
    public function render(\Quantyl\Answer\Answer $a) {
        $a->sendHeaders($this) ;
        echo $a->getContent() ;
    }
    
    public function header($str, $replace = true, $http_respons = null) {
        echo "--- Header : $str, $replace, $http_respons\n" ;
    }
    
    public static function Factory(Configuration $cfg) {
        global $argv ;
        return new CmdLineServer($cfg, $argv) ;
    }
    
    protected function decorate($response, $servicename) {
        return $response ;
    }

}
