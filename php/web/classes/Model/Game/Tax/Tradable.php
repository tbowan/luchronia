<?php

namespace Model\Game\Tax ;

class Tradable extends \Quantyl\Dao\BddObject {
    
    public function update() {
        if ($this->exists()) {
            parent::update();
        } else {
            parent::create() ;
        }
    }
    
    // Parser
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
    
    public static function GetFromInstance(\Model\Game\Building\Instance $i) {
        $temp = static::getSingleResult(
                "select * from `" . self::getTableName() . "`"
                . " where `instance` = :iid",
                array("iid" => $i->id)) ;
        if ($temp == null) {
            $temp = new Tradable() ;
            $temp->instance = $i ;
            $temp->order = 0 ;
            $temp->trans = 0 ;
        }
        return $temp ;
    }
    
}
