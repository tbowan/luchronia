<?php

namespace Model\Forum ;

class Vote extends \Quantyl\Dao\BddObject {
    
    // Parser
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "choice" :
            case "author" :
                return $value->getId() ;
            default:
                return $value ;
        }
    }
    
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "choice" :
                return Choice::createFromId($value) ;
            case "author" :
                return \Model\Game\Character::createFromId($value) ;
            default:
                return $value ;
        }
    }
    
    // Get Some set
    
    public static function GetFromChoice(Choice $ch) {
        $query = "select *"
                . " from `" . static::getTableName() . "`"
                . " where `choice` = :cid" ;
        
        return static::getResult($query, array("cid" => $ch->getId())) ;
    }
    
    public static function CountFromChoice(Choice $ch) {
        $query = "select count(*)"
                . " from `" . static::getTableName() . "`"
                . " where `choice` = :cid" ;
        
        $row = static::getSingleRow($query, array ("cid" => $cat->getId())) ;
        return intval($row["c"]) ;
    }
    
}
