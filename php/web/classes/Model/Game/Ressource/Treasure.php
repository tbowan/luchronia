<?php

namespace Model\Game\Ressource ;

class Treasure extends \Quantyl\Dao\BddObject {
    
    public function update() {
        if ($this->amount <= 0) {
            $this->delete() ;
        } else {
            parent::update();
        }
    }
    
    public function delGained() {
        if (! $this->infinite) {
            $this->amount -= $this->gained ;
            $this->gained = min($this->gained, $this->amount) ;
        }
    }

    public static function FromBddValue($name, $value) {
        switch($name) {
            case "job" :
                return ($value == null ? null : \Model\Game\Building\Job::GetById($value)) ;
            case "type" :
                return ($value == null ? null : \Model\Game\Building\Type::GetById($value)) ;
            case "biome" :
                return ($value == null ? null : \Model\Game\Biome::GetById($value)) ;
            case "city" :
                return ($value == null ? null : \Model\Game\City::GetById($value)) ;
            case "delivery" :
                return ($value == null ? null : \Model\Game\Ressource\Delivery::GetById($value)) ;
            case "item" :
                return \Model\Game\Ressource\Item::GetById($value) ;
            default:
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "job" :
            case "type" :
            case "biome" :
            case "city" :
            case "delivery" :
                return ($value == null ? null : $value->getId()) ;
            case "item" :
                return $value->getId() ;
            default:
                return $value ;
        }
    }
    
    public static function GetRandomFromOutside(\Model\Game\City $c) {
        $outside = \Model\Game\Building\Job::GetByName("Outside") ;
        return static::GetRandomFromSet(
                static::getResult(
                "select * from `" . self::getTableName() . "`"
                . " where"
                . "  ( isnull(`job`)   or `job`   = :jid ) and"
                . "  ( isnull(`type`)                    ) and"
                . "  ( isnull(`biome`) or `biome` = :bid ) and"
                . "  ( isnull(`city`)  or `city`  = :cid )",
                array(
                    "jid" => $outside->id,
                    "bid" => $c->biome->id,
                    "cid" => $c->id
                ))) ;
    }
    
    public static function GetRandomFromInstance(\Model\Game\Building\Instance $i) {
        
        if ($i->job->equals(\Model\Game\Building\Job::GetByName("Ruin"))) {
            return self::GetRandomFromRuin($i) ;
        } else {

            return static::GetRandomFromSet(
                    static::getResult(
                    "select * from `" . self::getTableName() . "`"
                    . " where"
                    . "  ( isnull(`job`)   or `job`   = :jid ) and"
                    . "  ( isnull(`type`)  or `type`  = :tid ) and"
                    . "  ( isnull(`biome`) or `biome` = :bid ) and"
                    . "  ( isnull(`city`)  or `city`  = :cid )",
                    array(
                        "jid" => $i->job->id,
                        "tid" => $i->type->id,
                        "bid" => $i->city->biome->id,
                        "cid" => $i->city->id
                    ))) ;
        }
    }
    
    public static function GetRandomFromRuin(\Model\Game\Building\Instance $i) {
        $ruin = \Model\Game\Building\Ruin::GetFromInstance($i) ;
        return static::GetRandomFromSet(
                static::getResult(
                "select * from `" . self::getTableName() . "`"
                . " where"
                . "  ( isnull(`job`)   or `job`   = :jid or `job` = :rid ) and"
                . "  ( isnull(`type`)  or `type`  = :tid ) and"
                . "  ( isnull(`biome`) or `biome` = :bid ) and"
                . "  ( isnull(`city`)  or `city`  = :cid )",
                array(
                    "jid" => $i->job->id,
                    "rid" => $ruin->job->id,
                    "tid" => $i->type->id,
                    "bid" => $i->city->biome->id,
                    "cid" => $i->city->id
                ))) ;
    }
    
    private static function GetRandomFromSet($set) {
        
        // 1. make table and compute sum
        $sum = 0 ;
        $res = array() ;
        foreach ($set as $elem) {
            $sum += $elem->amount ;
            $res[] = $elem ;
        }
        
        // 2. take a random value
        $rand = $sum * mt_rand() / mt_getrandmax() ;
        foreach ($res as $elem) {
            if ($elem->amount > $rand) {
                return $elem ;
            } else {
                $rand -= $elem->amount ;
            }
        }
        
        // We never go there
        return null ;
        
    }
    
    public static function GetFromDelivery(Delivery $d) {
        return self::getResult(""
                . " select *"
                . " from `" . self::getTableName() . "`"
                . " where"
                . "     delivery = :did",
                array("did" => $d->id)) ;
    }
    
    public static function CountFromDelivery(Delivery $d) {
        return self::getCount(""
                . " select count(*) as c"
                . " from `" . self::getTableName() . "`"
                . " where"
                . "     delivery = :did",
                array("did" => $d->id)) ;
    }
    
    

}
