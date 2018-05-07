<?php

namespace Model\Game\Ressource ;

class Parchment extends \Quantyl\Dao\BddObject {
    
    public function getLearningSkill() {
        $learn = \Model\Game\Skill\Learn::GetFromCharacteristic($this->skill->characteristic) ;
        return $learn->skill ;
    }
    
    // Parser
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "item" :
                return \Model\Game\Ressource\Item::GetById($value) ;
            case "skill" :
                return \Model\Game\Skill\Skill::GetById($value) ;
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
    
    public static function GetByItem(Item $i) {
        return static::getSingleResult(
                "select * from `" . self::getTableName() . "`"
                . " where `item` = :iid",
                array("iid" => $i->id)
                ) ;
    }
    
    public static function GetBySkill(\Model\Game\Skill\Skill $s) {
        return static::getSingleResult(
                "select * from `" . self::getTableName() . "`"
                . " where `skill` = :sid",
                array("sid" => $s->id)
                ) ;
    }
}
