<?php

namespace Model\Game\Ressource ;

use Model\Game\Character;
use Quantyl\Dao\BddObject;

class Inventory extends BddObject {

    public function update() {
        if ($this->amount <= 0) {
            $this->delete() ;
        } else {
            parent::update() ;
        }
    }
    
    public function isEquipable() {
        return \Model\Game\Ressource\Equipable::CanEquip($this->character, $this->item) ;
    }
    
    public function isEatable() {
        $eat = Eatable::GetByItem($this->item) ;
        return $eat !== null && $eat->canEat($this->character) ;
    }
    
    public function isDrinkable() {
        $drk = Drinkable::GetByItem($this->item) ;
        return $drk != null ;
    }
    
    public function isReadable() {
        $parch = Parchment::GetByItem($this->item) ;
        $book  =      Book::GetByItem($this->item) ;
        return $parch != null || $book != null ;
    }
    
    public function isModifier() {
        return \Model\Game\Ressource\Modifier::CanUse($this->character, $this->item) ;
    }
    
    public function isUsable() {
        return
                $this->isDrinkable() ||
                $this->isEatable()   ||
                $this->isModifier()  ||
                $this->isReadable() ;
    }
    
    public function getEnergyHydration() {
        $energy = 0;
        $hydration = 0;
        
        $eatable = \Model\Game\Ressource\Eatable::GetByItem($this->item);
        if ($eatable !== null && $eatable->canEat($this->character)) {
            $energy += $this->amount * $eatable->energy;
        }

        $drinkable = \Model\Game\Ressource\Drinkable::GetByItem($this->item);
        if ($drinkable !== null) {
            $hydration += $this->amount * $drinkable->hydration;
            if ($this->character->race->equals(\Model\Enums\Race::HUMAN())) {
                $energy += $this->amount * $drinkable->energy;
            }
        }
        return array($energy, $hydration) ;
    }
    
    public static function TotalEnergyHydration(Character $c) {
        $energy = 0 ;
        $hydration = 0 ;
        
        foreach (self::GetFromCharacter($c, true) as $inv) {
            list($e, $h) = $inv->getEnergyHydration() ;
            $energy     += $e ;
            $hydration  += $h ;
        }
        
        return array($energy, $hydration) ;
    }
    
    // Parser
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "item" :
                return Item::GetById($value) ;
            case "character" :
                return Character::GetById($value) ;
            case "slot" :
                if ($value == null) {
                    return null ;
                } else {
                    return Slot::GetById($value) ;
                }
            default:
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "item" :
            case "character" :
                return $value->getId() ;
            case "slot" :
                if ($value == null) {
                    return null ;
                } else {
                    return $value->getId() ;
                }
            default:
                return $value ;
        }
    }
    
    private static function AddItemExists(Character $c, Item $i, $amount) {
        $slots = static::GetFromItem($c, $i) ;
        foreach ($slots as $s) {
            $added = min(100 - $s->amount, $amount) ;
            $s->amount += $added ;
            $s->update() ;
            $amount -= $added ;
        }
        return $amount ;
    }
    
    private static function AddItemNext(Character $c, Item $i, $amount) {
        $used = static::InventoryUsed($c) ;
        $available = $c->inventory ;
        while ($amount > 0 && $used < $available) {
            $placed = min($amount, 100) ;
            static::createFromValues(array(
                "item" => $i,
                "character" => $c,
                "slot" => null,
                "amount" => $placed
            )) ;
            $amount -= $placed ;
            $used++ ;
        }
        return $amount ;
    }
    
    public static function AddItem(Character $c, Item $i, $amount) {
        $amount = self::AddItemExists($c, $i, $amount) ;
        $amount = self::AddItemNext($c, $i, $amount) ;
        return $amount ;
    }
    
    // return how many item remains for this character
    public static function DelItem(Character $c, Item $i, $amount) {
        $slots = static::GetFromItem($c, $i) ;
        $remain = 0 ;
        foreach ($slots as $s) {
            $removed = min($s->amount, $amount) ;
            $s->amount -= $removed ;
            $remain += $s->amount ;
            $s->update() ;
            $amount -= $removed ;
        }
        return $remain - $amount ;
    }
    
    public static function GetAvaiableSpace(Character $c, Item $i) {
        $res = 0 ;
        foreach (self::GetFromItem($c, $i) as $slot) {
            $res = 100 - $slot->amount ;
        }
        $slot_used = static::InventoryUsed($c) ;
        $available = $c->inventory ;
        
        $res += 100 * ($available - $slot_used) ;
        return $res ;
    }
    
    public static function GetEquipement(Character $c) {
        return static::getResult(
                "select * from `" . self::getTableName() . "`"
                . " where `character` = :cid and not isnull(`slot`)",
                array("cid" => $c->id)
                ) ;
    }
    
    public static function GetEquiped(Character $c, Slot $s) {
        return static::getResult(
                "select * from `" . self::getTableName() . "`"
                . " where `character` = :cid and `slot` = :sid",
                array(
                        "cid" => $c->id,
                        "sid" => $s->id
                        )
                ) ;
    }
    
    public static function GetFromItem(Character $c, Item $i, $amount = 0) {
        return static::getResult(
                "select * from `" . self::getTableName() . "`"
                . " where `character` = :cid and `item` = :iid and `amount` >= :a",
                array(
                        "cid" => $c->id,
                        "iid" => $i->id,
                        "a" => $amount
                        )
                ) ;
    }
    
    public static function GetMaps(Character $c) {
        return static::getResult(
                "select\n"
                . "  i.id as id,\n"
                . "  i.item as item,\n"
                . "  i.`character` as `character`\n"
                . " from `" . self::getTableName() . "` as i\n"
                . " join `game_building_map` as m\n"
                . " on i.item = m.item\n"
                . " where i.`character` = :cid",
                array(
                        "cid" => $c->id
                        )
                ) ;
    }
    
    public static function GetAmount(Character $c, Item $i) {
        $res = 0 ;
        foreach (static::GetFromItem($c, $i) as $inv) {
            $res += $inv->amount ;
        }
        return $res ;
    }
    
    public static function GetFromCharacter(Character $c, $all = false) {
        if ($all) {
            $where = "" ;
        } else {
            $where = " and isnull(`slot`)" ;
        }
        return static::getResult(
                "select * from `" . self::getTableName() . "`"
                . " where `character` = :cid $where",
                array("cid" => $c->id)
                ) ;
    }
    
    public static function InventoryUsed(Character $c) {
        $row = static::getSingleRow(
                "select count(*) as c from `" . self::getTableName() . "`"
                . " where `character` = :cid and isnull(`slot`)",
                array("cid" => $c->id)
                ) ;
        return intval($row["c"]) ;
    }
    
    public function group() {
        $remain = $this->amount ;
        
        foreach (self::GetFromItem($this->character, $this->item) as $inv) {
            if ($remain > 0 && $inv->amount < 100 && ! $inv->equals($this)) {
                $placed = min(100 - $inv->amount, $remain) ;
                $inv->amount += $placed ;
                $inv->update() ;
                $remain -= $placed ;
            }
        }
        
        $placed = $this->amount - $remain ;
        $this->amount = $remain ;
        return $placed ;
    }
    
}
