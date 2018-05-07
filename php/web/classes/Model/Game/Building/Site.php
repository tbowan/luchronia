<?php

namespace Model\Game\Building ;

class Site extends \Quantyl\Dao\BddObject {
    
    public function getNeedCompletion() {
        $max = 0 ;
        $pro = 0 ;
        
        foreach (Need::GetFromSite($this) as $n) {
            $max += $n->needed ;
            $pro += $n->provided ;
        }
        
        if ($max == 0) {
            return 1.0 ;
        } else {
            return $pro / $max ;
        }
    }
    
    public function getTargetHealth() {
        return $this->job->getMaxHealth($this->instance->level) ;
    }
    
    public function build($points) {
        $target = $this->getTargetHealth() ;
        $max_hp = $target * $this->getNeedCompletion() ;
        
        $hp = min($max_hp, $this->instance->health + $points) ;
        $this->instance->health = $hp ;
        if ($hp >= $target) {
            // Building site is closed
            $this->instance->job = $this->job ;
            $this->instance->update() ;
            $this->delete() ;
            return true ;
        } else {
            $this->instance->update() ;
            return false ;
        }
        
    }
    
    // parser
    
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "instance" :
                return Instance::GetById($value) ;
            case "job" :
                return Job::GetById($value) ;
            default:
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "instance" :
            case "job" :
                return $value->getId() ;
            default:
                return $value ;
        }
    }
    
    public static function GetFromInstance(Instance $i) {
        return static::getSingleResult(
                "select * from `" . self::getTableName() . "`"
                . " where `instance` = :iid",
                array("iid" => $i->id)) ;
    }
    
    public static function GetForPrefecture(Prefecture $pref) {
        return self::getResult(""
                . " select s.*"
                . " from"
                . "     `" . self::getTableName() . "` as s,"
                . "     `" . \Model\Game\Building\Instance::getTableName() . "` as i,"
                . "     `" . \Model\Game\City\Prefecture::getTableName() . "` as c"
                . " where"
                . "     s.instance = i.id and"
                . "     i.city = c.city and"
                . "     c.prefecture = :pid and"
                . "     c.distance <= :level"
                . " order by i.city",
                array(
                    "pid" => $pref->id,
                    "level" => $pref->instance->level
                )) ;
    }
    
}
