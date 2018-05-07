<?php

namespace Model\Game\Building ;

class Tradingpost extends \Quantyl\Dao\BddObject {
    
    public function getTradingMax() {
        $l = $this->instance->level ;
        return 5 * $l * ($l + 1) ;
    }
    
    public function getTradingUsed() {
        return \Model\Game\Trading\Tradingpost::GetCountFromInstance($this->instance) ;
    }
    
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "instance" :
                return \Model\Game\Building\Instance::GetById($value) ;
            default:
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "instance" :
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
}