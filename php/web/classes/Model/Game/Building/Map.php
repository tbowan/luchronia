<?php

namespace Model\Game\Building ;

class Map extends \Quantyl\Dao\BddObject {

    public function getHealth() {
        return $this->job->getMaxHealth($this->level) ;
    }
    
    public function getCosts() {
        return self::getCostBase($this->job, $this->type, $this->level) ;
    }
    
    // Parser
    
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "item" :
                return \Model\Game\Ressource\Item::GetById($value) ;
            case "job" :
                return Job::GetById($value) ;
            case "type" :
                return Type::GetById($value) ;
            case "skill" :
                return \Model\Game\Skill\Skill::GetById($value) ;
            default:
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "item" :
            case "job" :
            case "type" :
            case "skill" :
                return $value->getId() ;
            default:
                return $value ;
        }
    }
    
    public static function getFromJobTypeLevel(Job $j, Type $t, $level) {
        return static::getSingleResult(
                "select * from `" . self::getTableName() . "` where"
                . " `job`   = :job and"
                . " `type`  = :type and"
                . " `level` = :level",
                array (
                    "job"   => $j->id,
                    "type"  => $t->id,
                    "level" => $level
                )) ;
    }
    
    public static function getFromJob(Job $j) {
        return static::getResult(
                "select * from `" . self::getTableName() . "` where"
                . " `job`   = :job",
                array (
                    "job"   => $j->id
                )) ;
    }
    
    public static function getFromSkill(\Model\Game\Skill\Skill $s) {
        return static::getResult(
                "select * from `" . self::getTableName() . "` where"
                . " `skill` = :skill",
                array (
                    "skill"   => $s->id
                )) ;
    }
    
    public static function getFromItem(\Model\Game\Ressource\Item $i) {
        return static::getSingleResult(
                "select * from `" . self::getTableName() . "` where"
                . " `item` = :iid",
                array (
                    "iid"   => $i->id
                )) ;
    }
    
    public static function getCostBase(Job $j, Type $t, $l) {
        
        $points = $l * ($l + 1) / 2 ;
        $health = $j->health * $points ;
        
        $temp = array() ;
        foreach (\Model\Game\Building\Construction::getFromType($t) as $n) {
            $temp[$n->item->id] = $n->amount * $health;
        }
        
        foreach (\Model\Game\Building\Complement::getFromJob($j) as $n) {
            if (isset($temp[$n->item->id])) {
                $temp[$n->item->id] += $n->amount * $points;
            } else {
                $temp[$n->item->id] = $n->amount * $points;
            }
        }
        return $temp ;
    }
    
}
