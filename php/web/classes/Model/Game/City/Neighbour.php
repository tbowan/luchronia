<?php

namespace Model\Game\City ;

use Model\Game\City;
use Quantyl\Dao\BddObject;

class Neighbour extends BddObject {
    
    
    public function getPathCost() {
        return 2.0 / ($this->a->getTraversalCost() + $this->b->getTraversalCost()) ;
    }
    
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "a" :
            case "b" :
                return City::GetById($value) ;
            default :
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "a" :
            case "b" :
                return $value->getId() ;
            default :
                return $value ;
        }
    }
    
    public static function getFromA(City $a) {
        return static::getResult(
                "select * from `" . self::getTableName() . "`"
                . " where a = :a"
                . " order by `order`",
                array(
                    "a" => $a->getId(),
                       )
                ) ;
    }
    
    public static function getFromAB(City $a, City $b) {
        return static::getSingleResult(
                "select * from `" . self::getTableName() . "`"
                . " where a = :a and b = :b",
                array(
                    "a" => $a->getId(),
                    "b" => $b->getId(),
                        )
                ) ;
    }
    
}
