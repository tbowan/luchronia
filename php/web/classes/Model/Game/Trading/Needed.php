<?php

namespace Model\Game\Trading ;

class Needed extends \Quantyl\Dao\BddObject {
    
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "city" :
                return \Model\Game\City::GetById($value) ;
            case "item" :
                return \Model\Game\Ressource\Item::GetById($value) ;
            default:
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "city" :
            case "item" :
                return $value->getId() ;
            default:
                return $value ;
        }
    }
    
    public static function GetFromCityItem(\Model\Game\Ressource\Item $i, \Model\Game\City $city) {
        return static::getSingleResult(
                    "select *"
                    . " from `" . self::getTableName() . "`"
                    . " where `city` = :cid and `item` = :iid",
                    array("cid" => $city->getId(), "iid" => $i->getId())
                ) ;
    }
    
    public static function GetPrice(\Model\Game\Ressource\Item $i, \Model\Game\City $city) {
        $elem = self::GetFromCityItem($i, $city) ;
        return ($elem === null ? 0 : $elem->price) ;
    }
    
    public static function SetPrice(\Model\Game\Ressource\Item $i, \Model\Game\City $city, $price) {
        $elem = self::GetFromCityItem($i, $city) ;
        
        if ($elem == null) {
            return self::createFromValues(array(
                "city" => $city,
                "item" => $i,
                "price" => $price
            )) ;
        } else {
            $elem->price = $price ;
            $elem->update() ;
        }
    }
    
}
