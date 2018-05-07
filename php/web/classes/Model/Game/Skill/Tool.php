<?php

namespace Model\Game\Skill ;

class Tool extends \Quantyl\Dao\BddObject {
    
    public function getCoef() {
        return $this->coef * (1 + 0.0001 * $this->item->energy) ;
    }
    
    // Parser
    
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "item" :
                return \Model\Game\Ressource\Item::GetById($value) ;
            case "skill" :
                return Skill::GetById($value) ;
            default:
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "item" :
            case "skill" :
                return $value->getId() ;
            default:
                return $value ;
        }
    }
    
    public static function GetFromSkill(Skill $s) {
        return static::getResult(
                "select *"
                . " from `" . self::getTableName() . "`"
                . " where"
                . "     `skill` = :sid",
                array(
                    "sid" => $s->id
                )) ;
    }
    
    public static function GetFromItem(\Model\Game\Ressource\Item $i) {
        return static::getResult(
                "select *"
                . " from `" . self::getTableName() . "`"
                . " where"
                . "     `item` = :iid",
                array(
                    "iid" => $i->id
                )) ;
    }
    
    public static function GetFromSkillAndItem(Skill $s, \Model\Game\Ressource\Item $i) {
        return static::getSingleResult(
                "select *"
                . " from `" . self::getTableName() . "`"
                . " where"
                . "     `skill` = :sid and"
                . "     `item`  = :iid",
                array(
                    "sid" => $s->id,
                    "iid" => $i->id
                )) ;
    }
}
