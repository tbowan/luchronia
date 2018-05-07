<?php

namespace Model\Game\Skill ;

class Architect extends \Quantyl\Dao\BddObject {
    
    // Parser
    
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "type" :
                return \Model\Game\Building\Type::GetById($value) ;
            case "skill" :
                return Skill::GetById($value) ;
            default:
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "type" :
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
    
    public static function GetFromType(\Model\Game\Building\Type $t) {
        return static::getSingleResult(
                "select *"
                . " from `" . self::getTableName() . "`"
                . " where"
                . "     `type` = :tid",
                array(
                    "tid" => $t->id
                )) ;
    }
    
}
