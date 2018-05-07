<?php

namespace Model\Game\Skill ;

class Learn extends \Quantyl\Dao\BddObject {

    // Parser
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "characteristic" :
                return \Model\Game\Characteristic::GetById($value) ;
            case "skill" :
                return \Model\Game\Skill\Skill::GetById($value) ;
            default:
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "characteristic" :
            case "skill" :
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
    
    public static function GetFromCharacteristic(\Model\Game\Characteristic $c) {
        return static::getSingleResult(
                "select * from `" . self::getTableName() . "`"
                . " where `characteristic` = :sid",
                array("sid" => $c->id)) ;
    }
    
}
