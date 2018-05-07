<?php

namespace Model\Game\Politic ;

class RevolutionStatus extends \Quantyl\Dao\AbstractEnum {
    
    protected static $_enumeration = array (
        0 => "Secret",
        1 => "Declared",
        2 => "Ended"
    ) ;
    
    public function getPrefix() {
        return "REVOLUTION_STATUS_" ;
    }
    
    public static function getFromSystem(System $s) {
        if ($s->start == null) {
            return static::Secret() ;
        } else if ($s->end > time()) {
            return static::Declared() ;
        } else {
            return static::Ended() ;
        }
    }
    
}
