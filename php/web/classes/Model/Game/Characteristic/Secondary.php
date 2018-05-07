<?php

namespace Model\Game\Characteristic ;

class Secondary extends \Quantyl\Dao\BddObject {

    public static function FromBddValue($name, $value) {
        switch($name) {
            case "secondary" :
            case "base" :
                return \Model\Game\Characteristic::GetById($value) ;
            default:
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "secondary" :
            case "base" :
                return $value->getId() ;
            default:
                return $value ;
        }
    }
    
    public static function getFromSecondary(\Model\Game\Characteristic $cha) {
        return static::getResult(
                "select * from `" . self::getTableName() . "` where `secondary` = :id",
                array("id" => $cha->getId())
                ) ;
    }
    
}
