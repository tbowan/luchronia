<?php

namespace Model\Enums ;

class Citizenship extends \Quantyl\Dao\AbstractEnum {
    
    protected static $_enumeration = array (
        0 => "OPEN",       // free access
        1 => "ON_DEMAND" , // make a request that must be approved
        2 => "CLOSED",     // no access granted
    ) ;
    
    public function getPrefix() {
        return "CITIZENSHIP_" ;
    }
    
}
