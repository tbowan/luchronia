<?php

namespace Model\Game\Tax ;

class Metier extends Base {
    
    // Parser
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "city" :
                return \Model\Game\City::GetById($value) ;
            case "metier" :
                return \Model\Game\Skill\Metier::GetById($value) ;
            default:
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "city" :
            case "metier" :
                return $value->getId() ;
            default:
                return $value ;
        }
    }
    
    public static function GetFromCityAndMetier(\Model\Game\City $c, \Model\Game\Skill\Metier $m) {
        $temp = static::getSingleResult(
                "select * from `" . self::getTableName() . "`"
                . " where `city` = :cid and `metier` = :mid",
                array("cid" => $c->id, "mid" => $m->id)) ;
        if ($temp == null) {
            $temp = new Metier() ;
            $temp->city = $c ;
            $temp->metier = $m ;
            $temp->fix = 0 ;
            $temp->var = 0 ;
        }
        return $temp ;
    }
}
