<?php

namespace Model\Quantyl ;

class Config extends \Quantyl\Dao\BddObject {
    
    public static function GetFromKey($key) {
        return static::getSingleResult(
                "select *"
                . " from `" . static::getTableName() . "`"
                . " where `key` = :key",
                array("key" => $key)
                ) ;
    }
    
    public static function ValueFromKey($key, $default = null) {
        $temp = self::GetFromKey($key) ;
        if ($temp == null) {
            return $default;
        } else {
            return $temp->value ;
        }
    }
    
}