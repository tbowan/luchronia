<?php

namespace Model\Game\Politic ;

class SystemType extends \Quantyl\Dao\AbstractEnum {
    
    use \Model\Illustrable ;
    
    protected static $_enumeration = array (
        1 => "Anarchy",
        2 => "Monarchy" ,
        3 => "Senate" ,
        4 => "Republic" ,
        5 => "Parliamentary" ,
        6 => "Democracy" ,
        7 => "Revolution" ,
        // 8 => "Change"
    ) ;
    
    public function getPrefix() {
        return "SYSTEM_TYPE_" ;
    }
    
    public function getPoliticalSystem(System $s) {
        $type = $this->getValue() ;
        $classname = "\\Model\\Game\\Politic\\$type" ;
        
        $rfclass = new \ReflectionClass($classname) ;
        $rfMethod = $rfclass->getMethod("GetFromSystem") ;
        
        return $rfMethod->invoke(null, $s) ;
    }

    public function getImgPath() {
        return "/Media/icones/Model/SystemType/" . $this->getValue() . ".png" ;
    }

    public function isCompatible($townhall_level) {
        switch ($this->getId()) {
            case 1 :
            case 7 :
            case 8 :
                return false ;
            default:
                return $this->getId() <= $townhall_level + 1 ;
        }
    }
}
