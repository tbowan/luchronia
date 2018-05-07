<?php

namespace Model\Enums ;

class Sex extends \Quantyl\Dao\AbstractEnum {
    
    protected static $_enumeration = array (
        1 => "MALE" ,
        2 => "FEMALE",
    ) ;
    
    
    public function getImage($classname = null) {
        $filename  = "/media/icones/misc/" ;
        $filename .= ucfirst($this->getValue()) ;
        $filename .= ".png" ;
        
        if ($classname == null) {
            $class = "" ;
        } else {
            $class = "class=\"$classname\"" ;
        }
        
        return "<img"
                . " src=\"$filename\""
                . " alt=\"" . $this->getName() . "\""
                . $class
                . " />" ;
    }
    
    
}

