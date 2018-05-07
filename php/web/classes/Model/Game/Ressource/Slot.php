<?php

namespace Model\Game\Ressource ;

class Slot extends \Quantyl\Dao\BddObject {
    
    use \Model\NameTranslated ;

    public static function getNameField() {
        return "name" ;
    }

    public static function getNamePrefix() {
        return "SLOT_" ;
    }

}
