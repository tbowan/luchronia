<?php

namespace Model\Game\Skill ;

use Model\Game\Building\Instance;
use Model\Game\Character as MCharacter;
use Model\Game\Characteristic;
use Quantyl\Dao\BddObject;

class Character extends BddObject {
    
    use \Model\Illustrable ;
    
    public function getImgPath() {
        return $this->skill->getImgPath() ;
    }
    
    public function getName() {
        return $this->skill->getName() ;
    }
    
    public static function getTimeNeeded($mastery, $bonus = 0.0, $modifiers = 1.0) {
        return floor(10000 * $modifiers * 1 / log10(10 + $mastery + $bonus)) ;
    }
    
    public function getTimeCost() {
        return self::getTimeNeeded(
                $this->getMastery(),
                Characteristic\Character::getValue($this->character,$this->skill->characteristic),
                $this->character->getTimeModifier()
                ) ;
    }
    
    public static function getLevelThreshold($l) {
        return  100 * $l * ($l + 1) / 2 ;
    }
    
    public function getLearningThreshold() {
        return self::getLevelThreshold($this->level + 1 ) ;
    }
    
    public function learn($amount) {
        $this->learning += $amount ;
        while ($this->learning >= $this->getLearningThreshold()) {
            $this->level += 1 ;
        }
        $this->update() ;
    }
    
    public function getMastery() {
        return $this->skill->metier->getLevel($this->character) ;
    }
    
    public function addUse($amount = 1) {
        $this->uses += $amount ;
    }
    
    // Parser
    
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "character" :
                return MCharacter::GetById($value) ;
            case "skill" :
                return Skill::GetById($value) ;
            default:
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "character" :
            case "skill" :
                return $value->getId() ;
            default:
                return $value ;
        }
    }
    
    // Get from criteria
    
    public static function GetFromCharacterAndSkill(
            MCharacter $c,
            Skill $s
            ) {
        return static::getSingleResult(
                "select *"
                . " from `" . self::getTableName() . "`"
                . " where"
                . "     `skill`     = :sid and"
                . "     `character` = :cid",
                array(
                    "cid" => $c->id,
                    "sid"  => $s->id
                )) ;
    }
    
    public static function GetFromCharacter (
            MCharacter $c
            ) {
        return static::getResult(
                "select *"
                . " from `" . self::getTableName() . "`"
                . " where"
                . "     `character`     = :cid",
                array(
                    "cid" => $c->id,
                )) ;
    }
    
    public static function GetFromCharacterAndInstance(
            MCharacter $c,
            Instance $i
            ) {
        
        return static::getResult(
                "select c.*"
                . " from"
                . "     `" . self::getTableName() . "` as c,"
                . "     `" . Skill::getTableName() . "` as s"
                . " where"
                . "     c.skill         = s.id and"
                . "     c.`character`   = :cid and"
                . "     (isnull(s.building_job) or s.building_job  = :bj) and"
                . "     (isnull(s.building_type) or s.building_type = :bt) and"
                . "     s.classname <> \"Move\"",
                array(
                    "cid" => $c->id,
                    "bj"  => $i->job->id,
                    "bt"  => $i->type->id
                )) ;
        
    }
    
    public static function GetFromCharacterJob(
            MCharacter $c, \Model\Game\Building\Job $job
            ) {
        
        return static::getResult(
                "select c.*"
                . " from"
                . "     `" . self::getTableName() . "` as c,"
                . "     `" . Skill::getTableName() . "` as s"
                . " where"
                . "     c.skill         = s.id and"
                . "     c.`character`   = :cid and"
                . "     s.building_job  = :bj  and"
                . "     (isnull(s.building_type))",
                array(
                    "cid" => $c->id,
                    "bj"  => $job->id,
                )) ;
        
    }
    
    public static function CreateBase(MCharacter $c, Skill $s, $level = 0) {
        $res = new Character() ;
        $res->skill     = $s ;
        $res->character = $c ;
        $res->uses      = 0 ;
        $res->level     = $level ;
        $res->learning  = 0 ;
        $res->create() ;
        return $res ;
    }
    
    public static function LearnFromCharacterAndSkill(MCharacter $c, Skill $s, $amount) {
        
        $cs = self::GetFromCharacterAndSkill($c, $s) ;
        if ($cs == null) {
            $cs = Character::CreateBase($c, $s) ;
        }
        $cs->learn($amount) ;
        return $cs ;
    }
    
    public static function GetTeachable(MCharacter $teacher, MCharacter $student) {
        return self::getResult(""
                . " select t.*"
                . " from"
                . "     `" . self::getTableName() . "` as t"
                . " left join"
                . "     (select * from `" . self::getTableName() . "` where `character` = :sid) as s"
                . " on t.skill = s.skill"
                . " where"
                . "     ((isnull(s.uses)     or t.uses     > s.uses    ) or"
                . "      (isnull(s.learning) or t.learning > s.learning)) and"
                . "     t.`character` = :tid",
                array("tid" => $teacher->id, "sid" => $student->id)) ;
    }
    
}