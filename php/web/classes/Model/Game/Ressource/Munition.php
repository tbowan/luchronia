<?php

namespace Model\Game\Ressource ;

class Munition extends \Quantyl\Dao\BddObject {
    
    public function getCoef() {
        return $this->coef * 0.01 * $this->item->energy * $this->amount ;
    }
    
    // Parser
    
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "tool" :
                return \Model\Game\Skill\Tool::GetById($value) ;
            case "item" :
                return \Model\Game\Ressource\Item::GetById($value) ;
            default:
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "tool" :
            case "item" :
                return $value->getId() ;
            default:
                return $value ;
        }
    }
    
    public static function GetByWeapon(\Model\Game\Skill\Tool $t) {
        return static::getResult(
                "select * from `" . self::getTableName() . "`"
                . " where `tool` = :tid",
                array("tid" => $t->id)
                ) ;
    }
    
    public static function GetByMunition(Item $i) {
        return static::getResult(
                "select * from `" . self::getTableName() . "`"
                . " where `item` = :iid",
                array("iid" => $i->id)
                ) ;
    }
    
}
