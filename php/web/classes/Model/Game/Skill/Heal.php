<?php

namespace Model\Game\Skill ;

class Heal extends \Quantyl\Dao\BddObject {

    // Parser
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "race" :
                return \Model\Enums\Race::GetById($value) ;
            case "skill" :
                return \Model\Game\Skill\Skill::GetById($value) ;
            default:
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "race" :
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
    
    public static function GetFromRace(\Model\Enums\Race $r) {
        return static::getResult(
                "select * from `" . self::getTableName() . "`"
                . " where `race` = :rid",
                array("rid" => $r->getId())) ;
    }
}
