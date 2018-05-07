<?php

namespace Model\Game\Trading ;

class Nation extends \Quantyl\Dao\BddObject {
    
    public function update() {
        if ($this->amount === 0) {
            $this->delete() ;
        } else {
            parent::update() ;
        }
    }
    
    public function removeAmount($delta) {
        if ($this->amount !== null) {
            $this->amount -= $delta ;
        }
    }
    
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "nation" :
                return ($value == null ? null : \Model\Game\City::GetById($value)) ;
            case "item" :
                return \Model\Game\Ressource\Item::GetById($value) ;
            case "type" :
                return Type::GetById($value) ;
            default:
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "nation" :
                return ($value == null ? null : $value->getId()) ;
            case "item" :
            case "type" :
                return $value->getId() ;
            default:
                return $value ;
        }
    }
    
    public static function GetFromNation($nation) {
        
        if ($nation == null) {
            return static::getResult(
                    "select *"
                    . " from `" . self::getTableName() . "`"
                    . " where isnull(`nation`)"
                    . " order by `item`, `type`",
                    array()
                ) ;
        } else {
            return static::getResult(
                    "select *"
                    . " from `" . self::getTableName() . "`"
                    . " where `nation` = :nid"
                    . " order by `item`, `type`",
                    array("nid" => $nation->getId())
                ) ;
        }
    }
    
}
