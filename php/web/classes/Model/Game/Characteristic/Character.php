<?php

namespace Model\Game\Characteristic ;

class Character extends \Quantyl\Dao\BddObject {

    // Parser
    
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "character" :
                return \Model\Game\Character::GetById($value) ;
            case "characteristic" :
                return \Model\Game\Characteristic::GetById($value) ;
            default:
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "character" :
            case "characteristic" :
                return $value->getId() ;
            default:
                return $value ;
        }
    }
    
    public static function GetFromCharacterAndCharacteristic(
            \Model\Game\Character $c,
            \Model\Game\Characteristic $cha
            ) {
        
        return static::getSingleResult(
                "select *"
                . " from `" . self::getTableName() . "`"
                . " where"
                . "     `character`      = :cid and"
                . "     `characteristic` = :cha",
                array(
                    "cid" => $c->id,
                    "cha" => $cha->id
                )) ;
    }
    
    public static function getSecondaryValue(
            \Model\Game\Character $c,
            \Model\Game\Characteristic $cha
            ) {
        
        if ($cha->primary) {
            return 0 ;
        } else {

            $value = 0 ;
            $cnt = 0 ;
            foreach (Secondary::getFromSecondary($cha) as $secondary) {
                $value += self::getValue($c, $secondary->base) ;
                $cnt++ ;
            }
            
            return ($cnt == 0 ? 0 : $value / $cnt );
        }
    }
    
    public static function getPrimaryValue(
            \Model\Game\Character $c,
            \Model\Game\Characteristic $cha
            ) {
        $self = self::GetFromCharacterAndCharacteristic($c, $cha) ;
        return ($self !== null ? $self->value : 0) ;
    }
    
    public static function getEquipableBonus(
            \Model\Game\Character $c,
            \Model\Game\Characteristic $cha
            ) {
        $value = 0 ;
        foreach (Equipable::GetForCharacter($cha, $c) as $bonus) {
            $value += $bonus->bonus ;
        }
        return $value ;
    }
    
    public static function getModifierBonus(
            \Model\Game\Character $c,
            \Model\Game\Characteristic $cha
            ) {
        $value = 0 ;
        foreach (Modifier::getBonusFor($c, $cha) as $bonus) {
            $value += $bonus->bonus ;
        }
        return $value ;
    }
    
    public static function getValue(
            \Model\Game\Character $c,
            \Model\Game\Characteristic $cha
            ) {
        return 0
                + self::getPrimaryValue($c, $cha)
                + self::getSecondaryValue($c, $cha)
                + self::getEquipableBonus($c, $cha)
                + self::getModifierBonus($c, $cha)
            ;
    }
    
    public static function IncForCharacterAndCharacteristic(
            \Model\Game\Character $c,
            \Model\Game\Characteristic $cha,
            $inc
            ) {
        $temp = self::GetFromCharacterAndCharacteristic($c, $cha) ;
        if ($temp !== null) {
            $temp->value += $inc ;
            $temp->update() ;
        } else {
            $temp = self::createFromValues(array(
                "characteristic" => $cha,
                "character" => $c,
                "value" => $inc
            )) ;
        }
    }
    
}
