<?php

namespace Model\Stats\Game ;

class Moves extends \Quantyl\Dao\BddObject {
    
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "city" :
                return \Model\Game\City::GetById($value) ;
            case "character" :
                return \Model\Game\Character::GetById($value) ;
            default:
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "city" :
            case "character" :
                return $value->getId() ;
            default:
                return $value ;
        }
    }
    
    public static function GetFromCharacter(\Model\Game\Character $c) {
        return static::getResult(
                "select *"
                . " from `" . self::getTableName() . "`"
                . " where"
                . "  `character` = :cid",
                array("cid" => $c->getId())
                ) ;
    }
    
    public static function GetFromCity(\Model\Game\City $c) {
        return static::getResult(
                "select *"
                . " from `" . self::getTableName() . "`"
                . " where"
                . "  `city` = :cid",
                array("cid" => $c->getId())
                ) ;
    }
    
    public static function GetLastCity(\Model\Game\City $c) {
        return static::getSingleResult(
                "select *"
                . " from `" . self::getTableName() . "`"
                . " where"
                . "  `city` = :cid"
                . "  order by `last_visit` desc"
                . "  limit 1",
                array("cid" => $c->getId())
                ) ;
    }
    
    public static function GetFromCharacterCity(\Model\Game\Character $char, \Model\Game\City $city) {
        return static::getSingleResult(
                "select *"
                . " from `" . self::getTableName() . "`"
                . " where"
                . "  `city` = :city and"
                . "  `character` = :character",
                array(
                    "character" => $char->getId(),
                    "city" => $city->getId(),
                        )
                ) ;
    }
    
    public static function Visit(\Model\Game\Character $char, \Model\Game\City $city) {
        $temp = self::GetFromCharacterCity($char, $city) ;
        if ($temp == null) {
            self::createFromValues(array(
                "city" => $city,
                "character" => $char,
                "last_visit" => time()
            )) ;
        } else {
            $temp->last_visit = time() ;
            $temp->update() ;
        }
        $city->last_seen = $temp->last_visit ;
        $city->update() ;
    }
    
}
