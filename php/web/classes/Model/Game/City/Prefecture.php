<?php

namespace Model\Game\City ;

class Prefecture extends \Quantyl\Dao\BddObject {
    
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "city" :
                return \Model\Game\City::GetById($value) ;
            case "prefecture" :
                return \Model\Game\Building\Prefecture::GetById($value) ;
            default :
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "city" :
            case "prefecture" :
                return $value->getId() ;
            default :
                return $value ;
        }
    }
    
    public static function GetForCity(\Model\Game\City $city) {
        return self::getResult(""
                . " select c.*"
                . " from"
                . "     `" .                            self::getTableName() . "` as c,"
                . "     `" . \Model\Game\Building\Prefecture::getTableName() . "` as b,"
                . "     `" .   \Model\Game\Building\Instance::getTableName() . "` as i,"
                . "     `" .        \Model\Game\Building\Job::getTableName() . "` as j"
                . " where"
                . "     c.prefecture  = b.id    and"
                . "     b.instance    = i.id    and"
                . "     c.distance   <= i.level and"
                . "     c.city        = :cid and"
                . "     i.job         = j.id and"
                . "     j.name        = \"Prefecture\""
                . " order by c.distance",
                array("cid" => $city->id)) ;
    }
    
    public static function IsCloseEnough(\Model\Game\City $city, \Model\Game\Building\Prefecture $prefecture) {
        $res = self::getSingleResult(""
                . " select *"
                . " from `" . self::getTableName() . "`"
                . " where"
                . "     city = :cid and"
                . "     prefecture = :pid",
                array(
                    "cid" => $city->id,
                    "pid" => $prefecture->id
                )) ;
        return $res !== null & $res->distance <= $prefecture->instance->level ;
    }
    
    
}
