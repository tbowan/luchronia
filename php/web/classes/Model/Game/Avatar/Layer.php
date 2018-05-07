<?php

namespace Model\Game\Avatar ;

class Layer extends \Quantyl\Dao\AbstractEnum {
    
    use \Model\Illustrable ;
    
    public function getImgPath() {
        return 
                "/Media/icones/Model/Layer/"
                . $this->getId()
                . ".png"
                ;
    }
    
    protected static $_enumeration = array (
         0 => "Background",
         1 => "Face",
         2 => "NeckAccessory",
         3 => "Nose",
         4 => "NoseAccessory",
         5 => "Mouth",
         6 => "MouthAccessory",
         7 => "Eyebrows",
         8 => "EyebrowsAccessory",
         9 => "Eye",
        10 => "EyeAccessory",
        11 => "Hair",
        12 => "HairAccessory",
        99 => "Foreground"
    ) ;

    public function getPrefix() {
        return "AVATAR_LAYER_" ;
    }
    
    
}
