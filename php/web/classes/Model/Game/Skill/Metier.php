<?php

namespace Model\Game\Skill ;

class Metier extends \Quantyl\Dao\BddObject {
    
    use \Model\DescriptionTranslated ;
    
    use \Model\Illustrable ;
    
    public function getImgPath() {
        return "/Media/icones/Model/Metier/" . $this->name . ".png" ;
    }
    
    public static function getDescriptionPrefix() {
        return "METIER_DESCRIPTION_" ;
    }

    public static function getNameField() {
        return "name" ;
    }

    public static function getNamePrefix() {
        return "METIER_" ;
    }
    
    public function getUse(\Model\Game\Character $c) {
        return static::getCount(
                "select sum(uses) as c"
                . " from"
                . "     game_skill_character,"
                . "     game_skill_skill"
                . " where"
                . "     game_skill_skill.id = game_skill_character.skill and"
                . "     game_skill_character.`character` = :cid and"
                . "     game_skill_skill.`metier` = :mid",
                array(
                    "cid" => $c->id,
                    "mid" => $this->id
                )) ;
    }
    
    
    public function getTheBestCharacter() {
        foreach ($this->getBestCharacter() as $c) {
            return $c ;
        }
        return null ;
    }
    
    public function getBestCharacter($limit = 1, $base = 0) {
        return \Model\Game\Character::getResult(
                "select"
                . "     game_character.*,"
                . "     sum(uses) as uses"
                . " from"
                . "     game_character,"
                . "     game_skill_character,"
                . "     game_skill_skill"
                . " where"
                . "     game_character.id = game_skill_character.`character` and"
                . "     game_skill_skill.id = game_skill_character.skill and"
                . "     game_skill_skill.`metier` = :mid"
                . " group by"
                . "     game_character.id"
                . " having"
                . "     uses > 0"
                . " order by"
                . "     uses desc"
                . " limit $base, $limit",
                array(
                    "mid" => $this->id
                )) ;
    }
    
    public static function getThreshold($level) {
        return 5 * $level * ($level + 1) ;
    }
    
    public function getLevel(\Model\Game\Character $c, $uses = null) {
        if ($uses === null) {
            $uses = $this->getUse($c) ;
        }
        
        return floor((sqrt(25.0 + 20.0 * $uses) - 5.0) / 10.0) ;
    }
    
    public function getMedal(\Model\Game\Character $c, $uses = null) {
        return Medal::FactoryLevel($this->getLevel($c, $uses)) ;
    }
    
    public function getMedalImg($character, $class = null, $uses = null) {
        if ($character == null) {
            $level = 0 ;
        } else {
            $level = $this->getLevel($character, $uses) ;
        }
        
        $m =  Medal::FactoryLevel($level) ;
        return $m->getImage($this, $class) ;
    }
    
    // Parser
    
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "ministry" :
                return \Model\Game\Politic\Ministry::GetById($value) ;
            default:
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "ministry" :
                return $value->getId() ;
            default:
                return $value ;
        }
    }
    
    public static function getFromMinistry(\Model\Game\Politic\Ministry $m) {
        return static::getResult(
                "select * from `" . self::getTableName() . "` where `ministry` = :mid",
                array("mid" => $m->id)
                ) ;
    }
    
    public static function getBest(\Model\Game\Character $c, $number) {
        return static::getStatement(
                "select"
                . "     game_skill_metier.*,"
                . "     sum(game_skill_character.uses) as uses"
                . " from"
                . "     game_skill_metier,"
                . "     game_skill_character,"
                . "     game_skill_skill"
                . " where"
                . "     game_skill_metier.id = game_skill_skill.metier and"
                . "     game_skill_skill.id = game_skill_character.skill and"
                . "     game_skill_character.`character` = :cid"
                . " group by"
                . "     game_skill_metier.id"
                . " having"
                . "     uses > 0"
                . " order by"
                . "     uses desc"
                . " limit $number",
                array(
                        "cid" => $c->id)
                ) ;
    }
        
}
