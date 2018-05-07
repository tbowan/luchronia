<?php

namespace Model\Game ;

class Characteristic extends \Quantyl\Dao\BddObject {

    use \Model\DescriptionTranslated ;
    use \Model\Illustrable ;
    
    public static function getNameField() {
        return "name" ;
    }

    public static function getNamePrefix() {
        return "CHARACTERISTIC_NAME_" ;
    }
    
    public function getImgPath() {
        return 
                "/Media/icones/Model/Characteristic/"
                . $this->name . ".png"
                ;
    }

    public static function getDescriptionPrefix() {
        return "CHARACTERISTIC_DESCRIPTION_" ;
    }
    
    public static function GetPrimary() {
        return self::getResult("select * from `" . self::getTableName() . "` where `primary`", array()) ;
    }
    
    public static function GetSecondary() {
        return self::getResult("select * from `" . self::getTableName() . "` where not `primary`", array()) ;
    }

}
