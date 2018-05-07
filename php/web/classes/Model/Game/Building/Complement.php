<?php

namespace Model\Game\Building ;

class Complement extends \Quantyl\Dao\BddObject {
    
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "item" :
                return \Model\Game\Ressource\Item::GetById($value) ;
            case "job" :
                return Job::GetById($value) ;
            default:
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "item" :
            case "job" :
                return $value->getId() ;
            default:
                return $value ;
        }
    }
    
    public static function getFromJob(Job $j) {
        return static::getResult(
                "select * from `" . self::getTableName() . "` where"
                . " `job` = :jid",
                array (
                    "jid"   => $j->id
                )) ;
    }
    
}
