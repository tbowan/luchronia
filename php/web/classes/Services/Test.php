<?php

namespace Services ;

class Test extends \Quantyl\Service\EnhancedService {
    
    public function getView($data) {
        
        $packed = "" ;
        $packed .= pack("I", 123456) ;
        $packed .= pack("I", 789123) ;
        
        $msg = "<pre>" ;
        $msg .= bin2hex($packed) . "\n" ;
        $msg .= "</pre>" ;
        
        $unpacked = unpack("I*", $packed) ;
        
        $msg .= "<pre>" ;
        print_r($unpacked) ;
        $msg .= "</pre>" ;
        
        return new \Quantyl\Answer\Message($msg) ;
    }
    
}
