<?php

namespace Model\Game\Ressource ;

use Model\Game\City;
use Quantyl\Dao\BddObject;

class Prospection extends BddObject {

    // Parser
    
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "item" :
                return Item::GetById($value) ;
            case "city" :
                return City::GetById($value) ;
            case "character" :
                return \Model\Game\Character::GetById($value) ;
            default:
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "item" :
            case "city" :
            case "character" :
                return $value->getId() ;
            default:
                return $value ;
        }
    }
    
    public static function GetFromCityAndCharacter(City $city, \Model\Game\Character $char) {
        return static::getResult(
                "select *"
                . " from `" . self::getTableName() . "`"
                . " where `city` = :city and `character` = :char",
                array ("city" => $city->id, "char" => $char->id)
                ) ;
    }
    
    public static function HasBeenProspected(\Model\Game\Character $char, City $city, Item $item) {
        $cnt = static::getCount(""
                . " select count(*) as c"
                . " from `" . self::getTableName() . "`"
                . " where"
                . "     `city` = :city and"
                . "     `character` = :char and"
                . "     `item` = :item",
                array(
                    "city" => $city->id,
                    "char" => $char->id,
                    "item" => $item->id
                ),
                "c") ;
        return $cnt > 0 ;
    }
}
