<?php

namespace Model\Game\Ressource ;

use Model\Game\City as MCity;

class City extends \Quantyl\Dao\BddObject {
    
    public function update() {
        if ($this->amount <= 0) {
            $this->delete() ;
        } else {
            parent::update() ;
        }
    }
    
    // Parser
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "item" :
                return Item::GetById($value) ;
            case "city" :
                return MCity::GetById($value) ;
            case "instance" :
                return ($value == null ? null : \Model\Game\Building\Instance::GetById($value)) ;
            default:
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "item" :
            case "city" :
                return $value->getId() ;
            case "instance" :
                return ($value == null ? null : $value->getId()) ;
            default:
                return $value ;
        }
    }
    
    // Get some sets
    
    public static function getPublicStocks(MCity $city) {
        return static::getResult(
                "select *"
                . " from `" . self::getTableName() . "`"
                . " where"
                . "     `city` = :cid and"
                . "     isnull(`instance`)",
                array("cid" => $city->getId())) ;
    }
    
    public static function GetPublicStockForItem(MCity $city, Item $i) {
        return static::getResult(
                "select *"
                . " from `" . self::getTableName() . "`"
                . " where"
                . "     `city` = :cid and"
                . "     `item` = :iid and"
                . "     isnull(`instance`)",
                array("cid" => $city->getId(), "iid" => $i->getId())) ;
    }
    
    public static function GetFromCity(MCity $city) {
        return static::getResult(
                "select *"
                . " from `" . self::getTableName() . "`"
                . " where"
                . "     `city` = :cid"
                . " order by instance",
                array("cid" => $city->getId())) ;
    }
    
    public static function GetFromInstance(\Model\Game\Building\Instance $i) {
        return static::getResult(
                "select *"
                . " from `" . self::getTableName() . "`"
                . " where"
                . "     `instance` = :iid",
                array("iid" => $i->getId())) ;
    }
    
    public static function GetAmountFromInstance(\Model\Game\Ressource\Item $item, \Model\Game\Building\Instance $i) {
        return self::getCount(""
                . " select sum(amount) as c"
                . " from `" . self::getTableName() . "`"
                . " where"
                . "     `instance` = :instance and"
                . "     `item`     = :item",
                array(
                    "item" => $item->id,
                    "instance" => $instance->id
                )) ;
    }
    
    public static function CountFromInstance(\Model\Game\Building\Instance $i) {
        $res = static::getSingleRow(
                "select count(*) as c"
                . " from `" . self::getTableName() . "`"
                . " where"
                . "     `instance` = :iid",
                array("iid" => $i->getId())) ;
        return $res === false ? 0 : intval($res["c"]) ;
    }
    
    public static function GiveToCity(MCity $city, Item $i, $amount, \Model\Game\Character $donnor) {
        \Model\Game\City\Register::createFromValues(array(
            "character" => $donnor,
            "city" => $city,
            "ressource" => $i,
            "amount" => $amount
        )) ;
               
        $amount = self::DonateExists($city, $i, $amount) ;
        self::DonateNew($city, $i, $amount) ;

    }
    
    public static function Donate(MCity $city, Item $i, $amount) {
        $remain = self::DonateExists($city, $i, $amount) ;
        self::DonateNew($city, $i, $remain) ;
    }
    
    public static function DonateExists(MCity $city, Item $i, $amount) {
        foreach (self::GetPublicStockForItem($city, $i) as $st) {
            $given = min(100 - $st->amount, $amount) ;
            if ($given != 0) {
                $st->amount += $given ;
                $st->update() ;
                $amount -= $given ;
            }
        }
        return $amount ;
    }
    
    public static function DonateNew(MCity $city, Item $i, $amount) {
        while ($amount > 0) {
            $given = min(100, $amount) ;
            self::createFromValues(array(
                "city" => $city,
                "item" => $i,
                "amount" => $given,
                "instance" => null,
                "price" => null,
                "published" => 0
            )) ;
            $amount -= $given ;
        }
    }
    
    public function group() {
        
        $remain = $this->amount ;
        foreach ($this->getSame() as $stock) {
            if ($remain > 0 && $stock->amount < 100) {
                $placed = min(100 - $stock->amount, $remain) ;
                $stock->amount += $placed ;
                $stock->update() ;
                $remain -= $placed ;
            }
        }
        
        $placed = $this->amount - $remain ;
        $this->amount = $remain ;
        return $placed ;
        
    }
    
    public function getSame() {
        
        if ($this->instance == null) {
            $where = "isnull(instance)" ;
            $attrs = array() ;
        } else {
            $where = "instance = :instance" ;
            $attrs = array("instance" => $this->instance->id) ;
        }
        
        $where .= " and item = :item and id <> :id" ;
        $attrs["item"] = $this->item->id ;
        $attrs["id"]   = $this->id ;
        
        return static::getResult(
                "select *"
                . " from `" . self::getTableName() . "`"
                . " where $where",
                $attrs) ;
    }
    
}
