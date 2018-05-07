<?php

namespace Model\Game\Ressource ;

use Quantyl\Dao\BddObject;

class Item extends BddObject {
    
    use \Model\Illustrable ;
    
    use \Model\NameTranslated ;
    
    public function getImgPath() {
        return 
                "/Media/icones/Model/Item/"
                . $this->name . ".png"
                ;
    }

    public static function getNameField() {
        return "name" ;
    }

    public static function getNamePrefix() {
        return "ITEM_" ;
    }
    
    public static function GetMayBeNeeded() {
        return static::getResult(
                "select game_ressource_item.*"
                . " from game_ressource_item"
                . " join ("
                . "   select item from game_building_complement"
                . "   UNION"
                . "   select item from game_building_construction"
                . "   ) as temp"
                . " on game_ressource_item.id = temp.item",
                array()) ;
    }

    public static function GetBuyable($max = 0) {
        return static::getResult(""
                . " select *"
                . " from `" . self::getTableName() . "`"
                . " where"
                . "     not isnull(`price`) and"
                . "     (:max = 0 or `price` < :max)"
                . " order by `price` desc",
                array("max" => $max)) ;
    }
    
    public static function GetDeliverable($max = 0) {
        return static::getResult(""
                . " select *"
                . " from `" . self::getTableName() . "`"
                . " where"
                . "     `prestige` > 0 and"
                . "     (:max = 0 or `prestige` < :max)"
                . " order by `price` desc",
                array("max" => $max)) ;
    }
}
