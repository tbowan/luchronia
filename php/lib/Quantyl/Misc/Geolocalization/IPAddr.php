<?php

namespace Quantyl\Misc\Geolocalization ;

class IPAddr {

    private $_hex ;
    
    public function __construct($hex) {
        $this->_hex = $hex ;
    }
    
    public function getHex() {
        return $this->_hex ;
    }
    
    public function getBin() {
        return hex2bin($this->_hex) ;
    }
    
    public function getHumanReadable() {
        return inet_ntop(hex2bin($this->_hex)) ;
    }
    
    public function __toString() {
        return $this->getHumanReadable();
    }
    
    public static function FromIPv4($addr) {
        return new IPAddr(bin2hex(inet_pton($addr))) ;
    }
    
    public static function FromIPv6($addr) {
        return new IPAddr(bin2hex(inet_pton($addr))) ;
    }
    
}
