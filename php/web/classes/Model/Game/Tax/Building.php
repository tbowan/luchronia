<?php

namespace Model\Game\Tax ;

class Building extends Base {
    
    // Parser
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "city" :
                return \Model\Game\City::GetById($value) ;
            case "job" :
                return \Model\Game\Building\Job::GetById($value) ;
            default:
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "city" :
            case "job" :
                return $value->getId() ;
            default:
                return $value ;
        }
    }
    
    public static function GetFromCityAndJob(\Model\Game\City $c, \Model\Game\Building\Job $j) {
        $temp = static::getSingleResult(
                "select * from `" . self::getTableName() . "`"
                . " where `city` = :cid and `job` = :jid",
                array("cid" => $c->id, "jid" => $j->id)) ;
        if ($temp == null) {
            $temp = new Building() ;
            $temp->city = $c ;
            $temp->job = $j ;
            $temp->fix = 0 ;
            $temp->var = 0 ;
        }
        return $temp ;
    }
}
