<?php

namespace Model\Game\Ressource ;

class Ecosystem extends \Quantyl\Dao\BddObject {

    // Parser
    
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "item" :
                return \Model\Game\Ressource\Item::GetById($value) ;
            case "biome" :
                return ($value == null ? null : \Model\Game\Biome::GetById($value)) ;
            default:
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "item" :
                return $value->getId() ;
            case "biome" :
                return ($value == null ? null : $value->getId()) ;
            default:
                return $value ;
        }
    }
    
    public static function GetFromBiome(\Model\Game\Biome $b) {
        return static::getResult(
                "select *"
                . " from `" . self::getTableName() . "`"
                . " where"
                . "     isnull(`biome`) or"
                . "     `biome` = :bid",
                array ("bid" => $b->id)
                ) ;
    }
        
    public static function GetFromItem(Item $i) {
        return static::getResult(
                "select *"
                . " from `" . self::getTableName() . "`"
                . " where `item` = :iid",
                array ("iid" => $i->id)
                ) ;
    }
}
