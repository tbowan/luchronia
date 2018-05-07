<?php

namespace Model\Game\Tax ;

class Skill extends Base {
    
    // Parser
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "city" :
                return \Model\Game\City::GetById($value) ;
            case "skill" :
                return \Model\Game\Skill\Skill::GetById($value) ;
            default:
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "city" :
            case "skill" :
                return $value->getId() ;
            default:
                return $value ;
        }
    }
    
    public static function GetFromCityAndSkill(\Model\Game\City $c, \Model\Game\Skill\Skill $s) {
        $temp = static::getSingleResult(
                "select * from `" . self::getTableName() . "`"
                . " where `city` = :cid and `skill` = :sid",
                array("cid" => $c->id, "sid" => $s->id)) ;
        if ($temp == null) {
            $temp = new Skill() ;
            $temp->city = $c ;
            $temp->skill = $s ;
            $temp->fix = 0 ;
            $temp->var = 0 ;
        }
        return $temp ;
    }
}
