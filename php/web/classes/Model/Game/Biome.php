<?php

namespace Model\Game ;

class Biome extends \Quantyl\Dao\BddObject {
    
    use \Model\DescriptionTranslated ;
    use \Model\Illustrable ;
    
    public static function getNameField() {
        return "name" ;
    }
    
    public static function getNamePrefix() {
        return "BIOME_" ;
    }

    public static function getDescriptionPrefix() {
        return "BIOME_DESCRIPTION_" ;
    }

    public function getTemperature() {
        if ($this->tmin < 0) {
            return \I18n::COLD() ;
        } else {
            return \I18n::WARM() ;
        }
    }

    public function getImgPath() {
        return "/Media/icones/Model/Biome/" . $this->name . ".png" ;
    }

}
