<?php

namespace Model\Game\Skill ;

use Model\Game\Building\Job;
use Model\Game\Building\Type;
use Quantyl\Dao\BddObject;
use Services\Game\City;

class Skill extends BddObject {
    
    use \Model\DescriptionTranslated ;
    
    use \Model\Illustrable ;
    
    public function getImgPath() {
        return "/Media/icones/Model/Skill/" . $this->name . ".png" ;
    }
    
    // Parser
    
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "building_job" :
                return ($value == null ? null : Job::GetById($value)) ;
            case "building_type" :
                return ($value == null ? null : Type::GetById($value)) ;
            case "need_skill" :
                return ($value == null ? null : City::GetById($value)) ;
            case "characteristic" :
                return \Model\Game\Characteristic::GetById($value) ;
            case "metier" :
                return ($value == null ? null : Metier::GetById($value)) ; 
            default:
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "building_job" :
            case "building_type" :
            case "need_skill" :
            case "metier" :
                return ($value == null ? null : $value->getId()) ;
            case "characteristic" :
                return $value->getId() ;
            default:
                return $value ;
        }
    }

    public static function getDescriptionPrefix() {
        return "SKILL_DESCRIPTION_" ;
    }

    public static function getNameField() {
        return "name" ;
    }

    public static function getNamePrefix() {
        return "SKILL_" ;
    }
    
    public static function getFromClassName($classname) {
                
        return static::getResult(
                "select *"
                . " from `" . self::getTableName() . "`"
                . " where"
                . "     `classname`      = :cn",
                array(
                    "cn" => $classname
                )) ;
    }
    
    public static function GetFromItem(\Model\Game\Ressource\Item $i) {
        return static::getResult(
                "select s.id as id"
                . " from"
                . "   `" . self::getTableName() . "` as s,"
                . "   `" . In::getTableName(). "` as si,"
                . "   `" . Out::getTableName(). "` as so"
                . " where"
                . "   s.id = si.skill and s.id = so.skill and"
                . "   (si.item = :iid or so.item = :iid)"
                . " group by id",
                array(
                    "iid" => $i->id
                )) ;
    }
    
    public static function getFromJob(Job $j) {
                
        return static::getResult(
                "select *"
                . " from `" . self::getTableName() . "`"
                . " where"
                . "     `building_job` = :job",
                array(
                    "job" => $j->id
                )) ;
    }

    public static function GetByClassname($classname) {
                
        return static::getResult(
                "select *"
                . " from `" . self::getTableName() . "`"
                . " where"
                . "     `classname` = :cn",
                array(
                    "cn" => $classname
                )) ;
    }
    
    public static function getFromCharacteristic(\Model\Game\Characteristic $c) {
        return static::getResult(
                "select *"
                . " from `" . self::getTableName() . "`"
                . " where"
                . "     `characteristic` = :cid",
                array(
                    "cid" => $c->id
                )) ;
    }
        
    public static function getFromMetier(Metier $m) {
        return static::getResult(
                "select *"
                . " from `" . self::getTableName() . "`"
                . " where"
                . "     `metier` = :mid",
                array(
                    "mid" => $m->id
                )) ;
    }
    
    public static function getStart() {
        return static::getResult(
                "select *"
                . " from `" . self::getTableName() . "`"
                . " where"
                . "     `cost` = 0",
                array()) ;
    }
    
    public static function getBuyable(\Model\Game\Character $c) {
        return static::getResult(
                "select s.*"
                . " from `" . self::getTableName() . "` as s"
                . " left join"
                . "     ("
                . "     select *"
                . "     from `game_skill_character`"
                . "     where `character` = :cid"
                . "     ) as c"
                . " on c.skill = s.id"
                . " where"
                . "     `cost` > 0 and"
                . "     `cost` <= :p and"
                . "     isnull(c.id)"
                . " order by"
                . "     `cost`,"
                . "     `classname`",
                array(
                    "p" => $c->point,
                    "cid" => $c->getId()
                        )) ;
    }
    
    public static function GetFromInstance(\Model\Game\Building\Instance $i) {
        return static::getResult(
                "select *"
                . " from"
                . "     `" . self::getTableName() . "` as s"
                . " where"
                . "     (isnull(s.building_job)  or s.building_job = :jid ) and"
                . "     (isnull(s.building_type) or s.building_type = :tid)"
                ,
                array(
                    "jid" => $i->job->getId(),
                    "tid" => $i->type->getId(),
                        )) ;
    }
    
    public static function getUnknown(\Model\Game\Character $c, \Model\Game\Building\Instance $i) {
        return static::getResult(
                "select s.*"
                . " from"
                . "     `" . self::getTableName() . "` as s"
                . " left join"
                . "     ("
                . "     select *"
                . "     from `game_skill_character`"
                . "     where"
                . "         `character` = :cid"
                . "     ) as c"
                . " on c.skill = s.id"
                . " where"
                . "     (isnull(s.building_job)  or s.building_job = :jid ) and"
                . "     (isnull(s.building_type) or s.building_type = :tid) and"
                . "     (isnull(c.id) or c.level = 0)"
                . " order by"
                . "     `cost`,"
                . "     `classname`",
                array(
                    "jid" => $i->job->getId(),
                    "tid" => $i->type->getId(),
                    "cid" => $c->getId()
                        )) ;
    }
    
    

    
}
