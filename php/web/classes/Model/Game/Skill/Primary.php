<?php

namespace Model\Game\Skill ;

class Primary extends \Quantyl\Dao\BddObject {
    
    // Parser
    
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "in" :
            case "out" :
                return \Model\Game\Ressource\Item::GetById($value) ;
            case "skill" :
                return Skill::GetById($value) ;
            default:
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "in" :
            case "out" :
            case "skill" :
                return $value->getId() ;
            default:
                return $value ;
        }
    }
    
    public static function GetFromSkill(Skill $s) {
        return static::getSingleResult(
                "select *"
                . " from `" . self::getTableName() . "`"
                . " where"
                . "     `skill` = :sid",
                array(
                    "sid" => $s->id
                )) ;
    }
    
    public static function GetFromIn(\Model\Game\Ressource\Item $i) {
        return static::getResult(
                "select *"
                . " from `" . self::getTableName() . "`"
                . " where"
                . "     `in` = :iid",
                array(
                    "iid" => $i->id
                )) ;
    }
    
    public static function GetFromOut(\Model\Game\Ressource\Item $i) {
        return static::getResult(
                "select *"
                . " from `" . self::getTableName() . "`"
                . " where"
                . "     `out` = :iid",
                array(
                    "iid" => $i->id
                )) ;
    }
    
    public static function GetFromInOut(\Model\Game\Ressource\Item $i) {
        return static::getResult(
                "select *"
                . " from `" . self::getTableName() . "`"
                . " where"
                . "     `out` = :iid or `in` = :iid",
                array(
                    "iid" => $i->id
                )) ;
    }
    
}
