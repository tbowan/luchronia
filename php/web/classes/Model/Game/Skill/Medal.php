<?php

namespace Model\Game\Skill ;

class Medal extends \Quantyl\Dao\AbstractEnum {
    
    protected static $_enumeration = array (
        0  => "None",
        1  => "Wood" ,
        5  => "Bronze",
        10 => "Silver" ,
        15 => "Gold" ,
        30 => "Platinium" ,
        60 => "Cristal" ,
    ) ;
    
    public function getPrefix() {
        return "MEDAL_" ;
    }
    
    public function getImage(Metier $metier, $class = null) {
        $filename = "/Media/icones/Model/Metier/" ;
        $filename .= $metier->name ;
        if ($this->getId() != 0) {
            $filename .= "_" . $this->getValue() ;
        }
        $filename .= ".png" ;
        
        return new \Quantyl\XML\Html\Img($filename, $this->getName(), $class) ;
    }
    
    
    public static function FactoryLevel($level) {
        if ($level >= 60) {
            return self::Cristal() ;
        } else if ($level >= 30) {
            return self::Platinium() ;
        } else if ($level >= 15) {
            return self::Gold() ;
        } else if ($level >= 10) {
            return self::Silver() ;
        } else if ($level >= 5) {
            return self::Bronze() ;
        } else if ($level >= 1) {
            return self::Wood() ;
        } else {
            return self::None() ;
        }
    }
    
}
