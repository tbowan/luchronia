<?php

namespace Model\Game\Building ;

class Type extends \Quantyl\Dao\BddObject {
    
    use \Model\DescriptionTranslated ;

    public static function getNameField() {
        return "name" ;
    }

    public static function getNamePrefix() {
        return "BUILDING_TYPE_" ;
    }

    public static function getDescriptionPrefix() {
        return "BUILDING_TYPE_DESCRIPTION_" ;
    }

}
