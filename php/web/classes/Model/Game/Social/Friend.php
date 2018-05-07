<?php

namespace Model\Game\Social ;

use Model\Game\Character;
use Quantyl\Dao\BddObject;

class Friend extends BddObject {

    public function delete() {
        parent::delete();
        
        $other = self::GetFromAB($this->b, $this->a) ;
        if ($other != null) {
            $other->delete() ;
        }
        
    }
    
    public function create() {
        if ($this->date == null) {
            $this->date = time() ;
        }
        parent::create();
        
        $other = self::GetFromAB($this->b, $this->a) ;
        if ($other == null) {
            $other       = new Friend() ;
            $other->a    = $this->b ;
            $other->b    = $this->a ;
            $other->date = $this->date ;
            $other->create() ;
        }
    }
    
    public function update() {
        // TODO : better exception
        throw new \Exception() ;
    }
    
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "a" :
            case "b" :
                return Character::GetById($value) ;
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
                . " where a = :a and b = :b",
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
    
    public static function areFriends(Character $a, Character $b) {
        $item = self::GetFromAB($a, $b) ;
        return $item != null || $a->equals($b);
    }
    
    
}
