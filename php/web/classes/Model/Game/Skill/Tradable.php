<?php

namespace Model\Game\Skill ;

class Tradable extends \Quantyl\Dao\BddObject {
    
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "job" :
                return \Model\Game\Building\Job::GetById($value) ;
            case "skill" :
                return \Model\Game\Skill\Skill::GetById($value) ;
            default:
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "job" :
            case "skill" :
                return $value->getId() ;
            default:
                return $value ;
        }
    }
    
    public static function GetFromJob(\Model\Game\Building\Job $j) {
        return self::getResult(
                "select * from `" . self::getTableName() . "` where `job` = :jid",
                array("jid" => $j->getId())
            ) ;
    }
    
}
