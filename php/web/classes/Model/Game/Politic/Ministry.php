<?php

namespace Model\Game\Politic ;

class Ministry extends \Quantyl\Dao\BddObject {
    
    use \Model\Illustrable ;
    
    use \Model\DescriptionTranslated ;
    
    public function getImgPath() {
        return "/Media/icones/Model/Ministry/{$this->name}.png" ;
    }

    public static function getDescriptionPrefix() {
        return "MINISTRY_DESCRIPTION_" ;
    }

    public static function getNameField() {
        return "name" ;
    }

    public static function getNamePrefix() {
        return "MINISTRY_NAME_" ;
    }
    
}
