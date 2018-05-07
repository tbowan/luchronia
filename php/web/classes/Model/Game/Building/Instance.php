<?php

namespace Model\Game\Building ;

class Instance extends \Quantyl\Dao\BddObject {

    use \Model\Illustrable ;
    
    public function create() {
        parent::create();
        
        $this->getTrueObject()->onCreate() ;
    }
    
    public function getImgPath() {
        return "/Media/icones/Model/Building/"
                . $this->type->name . "/"
                . $this->job->name . ".png" ;
    }
    
    public function getName() {
        $to = $this->getTrueObject() ;
        return $to->getName() ;
    }
    
    public function getWear() {
        return $this->job->wear * $this->type->wear ;
    }
    
    public function getFitness() {
        if ($this->health <= 0) {
            return 0 ;
        } else {
            $n = $this->level ;
            return 0.5 * $n * ($n + 1) * $this->job->fitness * $this->type->fitness ;
        }
    }
    
    public function getPrestige() {
        if ($this->health <= 0) {
            return 0 ;
        } else {
            $n = $this->level ;
            return 0.5 * $n * ($n + 1) * $this->job->prestige * $this->type->prestige ;
        }
    }
    
    public function acceptStock(\Model\Game\Ressource\Item $item) {
        $to = $this->getTrueObject() ;
        return $to->acceptStock($item) ;
    }
    
    public function getMaxHealth() {
        $o = $this->getTrueObject() ;
        return $o->getMaxHealth() ;
        
    }
    
    public function getRessources() {
        $o = $this->getTrueObject() ;
        return $o->getRessources() ;
    }
    
    public function getCosts() {
        $o = $this->getTrueObject() ;
        return $o->getCostBase() ;
        
    }
    
    public function getMap() {
        return Map::getFromJobTypeLevel($this->job, $this->type, $this->level) ;
    }
    
    public function getTrueObject() {
        return Jobs\Factory::createFromInstance($this) ;
    }
    
    public function getHealth() {
        return round($this->health, 2) ;
    }
    
    public function canManage(\Model\Game\Character $me) {
        return \Model\Game\Politic\Minister::hasPower(
                            $me,
                            $this->city,
                            $this->job->ministry) ;
    }
    
    // Update from cron
    
 
    
    // parser
    
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "city" :
                return \Model\Game\City::GetById($value) ;
            case "job" :
                return Job::GetById($value) ;
            case "type" :
                return Type::GetById($value) ;
            default:
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "city" :
            case "job" :
            case "type" :
                return $value->getId() ;
            default:
                return $value ;
        }
    }
    
    // Get some sets
    
    public static function GetSumLevel(\Model\Game\City $c) {
        $res = static::getSingleRow(
                "select sum(level) as c from `" . self::getTableName() . "`"
                . " where city = :cid and health > 0",
                array("cid" => $c->id)) ;
        return ($res !== false ? intval($res["c"]) : 0) ;
    }
    
    public static function GetFromCity(\Model\Game\City $c) {
        return static::getResult(
                "select * from `" . self::getTableName() . "`"
                . " where city = :cid and health > 0",
                array("cid" => $c->id)) ;
    }
    
    public static function CountFromCity(\Model\Game\City $c) {
        $res = static::getSingleRow(
                "select count(*) as c from `" . self::getTableName() . "`"
                . " where city = :cid and health > 0",
                array("cid" => $c->id)) ;
        return ($res !== false ? intval($res["c"]) : 0) ;
    }
    
    public static function GetForUpdate(\Model\Game\City $c) {
        return static::getResult(
                "select * from `" . self::getTableName() . "`"
                . " where city = :cid"
                . " order by health + barricade desc",
                array("cid" => $c->id)) ;
    }
    
    public static function GetFromJob(Job $j) {
        return static::getResult(
                "select * from `" . self::getTableName() . "`"
                . " where job = :jid and health > 0",
                array("jid" => $j->id)) ;
    }
    
    public static function GetFromCityAndJob(\Model\Game\City $c, Job $j) {
        return static::getResult(
                "select * from `" . self::getTableName() . "`"
                . " where city = :cid and job = :jid and health > 0",
                array("cid" => $c->id, "jid" => $j->id)) ;
    }
    
    public static function CountFromCityAndJob(\Model\Game\City $c, Job $j) {
        $res = static::getSingleRow(
                "select count(*) as c from `" . self::getTableName() . "`"
                . " where city = :cid and job = :jid and health > 0",
                array("cid" => $c->id, "jid" => $j->id)) ;
        return ($res !== false ? intval($res["c"]) : 0) ;
    }
    
    public static function HasCityJob(\Model\Game\City $c, Job $j) {
        $res = static::getSingleRow(
                "select count(*) as c from `" . self::getTableName() . "`"
                . " where city = :cid and job = :jid and health > 0",
                array("cid" => $c->id, "jid" => $j->id)) ;
        return ($res !== false ? intval($res["c"]) > 0 : false) ;
    }
    
    public static function GetLostRuins(\Model\Game\City $c) {
        return static::getResult(
                "select * from `" . self::getTableName() . "`"
                . " where city = :cid and health <= 0",
                array("cid" => $c->id)) ;
    }
    
    public static function GetAllFromCity(\Model\Game\City $c) {
        return static::getResult(
                "select * from `" . self::getTableName() . "`"
                . " where city = :cid",
                array("cid" => $c->id)) ;
    }
}
