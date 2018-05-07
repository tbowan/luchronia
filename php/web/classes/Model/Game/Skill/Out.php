<?php

namespace Model\Game\Skill ;

class Out extends \Quantyl\Dao\BddObject {
    
    // Parser
    
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "item" :
                return \Model\Game\Ressource\Item::GetById($value) ;
            case "skill" :
                return Skill::GetById($value) ;
            default:
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "item" :
            case "skill" :
                return $value->getId() ;
            default:
                return $value ;
        }
    }
    
    public static function GetFromSkill(Skill $s) {
        return static::getResult(
                "select *"
                . " from `" . self::getTableName() . "`"
                . " where"
                . "     `skill` = :sid",
                array(
                    "sid" => $s->id
                )) ;
    }
    
    public static function GetFromItem(\Model\Game\Ressource\Item $i) {
        return static::getResult(
                "select *"
                . " from `" . self::getTableName() . "`"
                . " where"
                . "     `item` = :iid",
                array(
                    "iid" => $i->id
                )) ;
    }
    
}
