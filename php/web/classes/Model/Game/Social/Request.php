<?php

namespace Model\Game\Social ;

use Model\Game\Character ;

class Request extends \Quantyl\Dao\BddObject {

    public function create() {
        if ($this->date == null) {
            $this->date = time() ;
        }
        parent::create() ;
    }
    
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "a" :
            case "b" :
                return \Model\Game\Character::GetById($value) ;
            default:
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "a" :
            case "b" :
                return $value->getId() ;
            default:
                return $value ;
        }
    }
    
    public static function GetFromAB(Character $a, Character $b) {
        return static::getSingleResult(
                "select * from `" . self::getTableName() . "`"
                . " where"
                . " (a = :a and b = :b)",
                array(
                    "a" => $a->getId(),
                    "b" => $b->getId(),
                        )
                ) ;
    }
    
    
    public static function GetFromA(Character $char) {
        return static::getResult(
                "select * from `" . self::getTableName() . "`"
                . " where a = :id",
                array("id" => $char->getId())
                ) ;
    }
    
    public static function GetFromB(Character $char) {
        return static::getResult(
                "select * from `" . self::getTableName() . "`"
                . " where b = :id",
                array("id" => $char->getId())
                ) ;
    }
    
    public static function HasFromB(Character $char) {
        $res = static::getSingleRow(
                "select count(*) as c from `" . self::getTableName() . "`"
                . " where b = :id",
                array("id" => $char->getId())
                ) ;
        return $res !== false && $res["c"] > 0;
    }
}
