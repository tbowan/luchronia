<?php

namespace Model\Game\Ressource ;

use Model\Game\City;
use Quantyl\Dao\BddObject;

class Natural extends BddObject {

    // Parser
    
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "item" :
                return Item::GetById($value) ;
            case "city" :
                return City::GetById($value) ;
            default:
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "item" :
            case "city" :
                return $value->getId() ;
            default:
                return $value ;
        }
    }
    
    public static function GetFromCity(City $c) {
        return static::getResult(
                "select *"
                . " from `" . self::getTableName() . "`"
                . " where `city` = :cid",
                array ("cid" => $c->id)
                ) ;
    }
    
    public static function GetFieldableFromCity(City $c) {
        return static::getResult(
                "select n.id as id, n.item as item"
                . " from `" . self::getTableName() . "` as n"
                . " join game_skill_field as f"
                . " on n.item = f.item"
                . " where `city` = :cid",
                array ("cid" => $c->id)
                ) ;
    }
    
    public static function GetFromCityAndItem(City $c, Item $i) {
        return static::getSingleResult(
                "select *"
                . " from `" . self::getTableName() . "`"
                . " where"
                . "  `city` = :cid and"
                . "  `item` = :iid",
                array (
                    "cid" => $c->id,
                    "iid" => $i->id
                        )
                ) ;
    }
    
}
