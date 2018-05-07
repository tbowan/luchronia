<?php

namespace Model\Game\Building ;

class Wall extends \Quantyl\Dao\BddObject {
    
    public function canEnter(\Model\Game\Character $char) {
        return $this->door->canEnter($char, $this->instance->city) ;
    }
    
    // parser
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "instance" :
                return Instance::GetById($value) ;
            case "door" :
                return \Model\Enums\Door::GetById($value) ;
            default:
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "instance" :
            case "door" :
                return $value->getId() ;
            default:
                return $value ;
        }
    }
    
    public static function GetFromInstance(Instance $i) {
        return static::getSingleResult(
                "select * from `" . self::getTableName() . "`"
                . " where `instance` = :iid",
                array("iid" => $i->id)) ;
    }
    
    public static function GetFromCity(\Model\Game\City $c) {
        $instances = \Model\Game\Building\Instance::GetFromCityAndJob($c, \Model\Game\Building\Job::GetByName("Wall"));
        foreach ($instances as $i) {
            return static::GetFromInstance($i) ;
        }
        return null ;
    }
}
