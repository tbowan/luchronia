<?php

namespace Model\Game\Tax ;

class Stranger extends Base {
    
    // Parser
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "city" :
                return \Model\Game\City::GetById($value) ;
            default:
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "city" :
                return $value->getId() ;
            default:
                return $value ;
        }
    }
    
    public static function GetFromCity(\Model\Game\City $c) {
        $temp = static::getSingleResult(
                "select * from `" . self::getTableName() . "`"
                . " where `city` = :cid",
                array("cid" => $c->id)) ;
        if ($temp == null) {
            $temp = new Stranger() ;
            $temp->city = $c ;
            $temp->fix = 0 ;
            $temp->var = 0 ;
        }
        return $temp ;
    }

}
