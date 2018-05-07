<?php

namespace Model\Game\Building ;

class Job extends \Quantyl\Dao\BddObject {
    
    use \Model\DescriptionTranslated ;
    
    public function getMaxHealth($level) {
        return $this->health * $level * ($level + 1) / 2.0 ;
    }
    
    public static function getNameField() {
        return "name" ;
    }

    public static function getNamePrefix() {
        return "BUILDING_JOB_" ;
    }

    public static function getDescriptionPrefix() {
        return "BUILDING_JOB_DESCRIPTION_" ;
    }
    
    // parser
    
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "ministry" :
                return \Model\Game\Politic\Ministry::GetById($value) ;
            default:
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "ministry" :
                return $value->getId() ;
            default:
                return $value ;
        }
    }
    
    public static function GetFromMinistry(\Model\Game\Politic\Ministry $m) {
        return static::getResult(
                "select * from `" . self::getTableName() . "`"
                . " where `ministry` = :mid ",
                array("mid" => $m->id)) ;
    }
    
}
