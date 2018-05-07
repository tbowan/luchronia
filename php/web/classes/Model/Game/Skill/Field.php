<?php

namespace Model\Game\Skill ;

use Model\Game\Ressource\Item;
use Quantyl\Dao\BddObject;

class Field extends BddObject {
    
    // parser
    
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "skill" :
                return Skill::GetById($value) ;
            case "item" :
            case "gain" :
                return Item::GetById($value) ;
            default:
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "skill" :
            case "item" :
            case "gain" :
                return $value->getId() ;
            default:
                return $value ;
        }
    }
    
    public static function GetFromSkill(Skill $s) {
        return static::getSingleResult(
                "select * from `" . self::getTableName() . "`"
                . " where `skill` = :sid",
                array("sid" => $s->id)) ;
    }
    
    public static function GetFromGain(Item $i) {
        return static::getResult(
                "select * from `" . self::getTableName() . "`"
                . " where `gain` = :iid",
                array("iid" => $i->id)) ;
    }
    
    public static function GetFromItem(Item $i) {
        return static::getResult(
                "select * from `" . self::getTableName() . "`"
                . " where `item` = :iid",
                array("iid" => $i->id)) ;
    }
    
}
