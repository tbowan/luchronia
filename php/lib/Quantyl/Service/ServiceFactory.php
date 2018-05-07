<?php

namespace Quantyl\Service ;

use \Quantyl\Configuration\Configuration ;

class ServiceFactory {
    
    private $_config ;
    
    public function __construct(Configuration $cfg) {
        $this->_config = $cfg ;
    }
    
    public function createServiceFromName($servicename) {
        $base = $this->_config["service.base"] ;
        
        $classname = "\\$base\\" . str_replace("/", "\\", $servicename) ;
        
        if (class_exists($classname)) {
            return new $classname() ;
        } else if ( class_exists("$classname\\Main")) {
            $cn = "$classname\\Main" ;
            return new $cn() ;
        } else {
            throw new \Quantyl\Exception\Http\ClientNotFound() ;
        }
    }
    
    public function createNameFromService(Service $srv) {
        $base = $this->_config->get["service.base"] ;
        
        $classname = get_class($s) ;
        
        $name = str_replace("\\", "/",
                    preg_replace("/Main$/", "",
                        preg_replace("/^$base\\\\/", "", $classname)
                    )
                );
        
        return $name ;
    }
    
    public function createURL($hostname, Service $srv, $params) {
        
        $servicename = $this->createNameFromService($srv) ;
        
        $temp = array() ;
        foreach ($params as $key=>$value) {
            $temp[] = urlencode($key) ."=" . urlencode($value) ;
        }
        
        return "http://$hostname/$servicename?" . implode("&", $temp) ;
        
    }
    
}
