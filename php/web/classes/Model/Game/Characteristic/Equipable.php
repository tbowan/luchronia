<?php

namespace Model\Game\Characteristic ;

class Equipable extends \Quantyl\Dao\BddObject {

    public static function FromBddValue($name, $value) {
        switch($name) {
            case "equipable" :
                return \Model\Game\Ressource\Equipable::GetById($value) ;
            case "characteristic" :
                return \Model\Game\Characteristic::GetById($value) ;
            default:
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "equipable" :
            case "characteristic" :
                return $value->getId() ;
            default:
                return $value ;
        }
    }
    
    public static function getFromCharacteristic(\Model\Game\Characteristic $cha) {
        return static::getResult(
                "select * from `" . self::getTableName() . "` where `characteristic` = :id",
                array("id" => $cha->getId())
                ) ;
    }
    
    public static function GetForCharacter(\Model\Game\Characteristic $carac, \Model\Game\Character $char) {
        
        return static::getResult(
                "   select bonus.* from"
                . "     `" . self::getTableName() . "` as bonus,"
                . "     `game_ressource_inventory` as inventory,"
                . "     `game_ressource_equipable` as equipable"
                . " where"
                . "     `inventory`.`character`  = :cid and"
                . "     `inventory`.`item`       = `equipable`.`item` and"
                . "     `inventory`.`slot`       = `equipable`.`slot` and"
                . "     `bonus`.`equipable`      = `equipable`.`id` and"
                . "     `bonus`.`characteristic` = :caid"
                ,
                array(
                    "cid" => $char->getId(),
                    "caid" => $carac->getId()
                        )) ;
        
    }
    
}
