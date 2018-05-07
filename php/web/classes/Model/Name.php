<?php

namespace Model ;

trait Name {
    
    public static abstract function getNameField() ;
    
    public function getName() {
        $field = static::getNameField() ;
        return $this->$field ;
    }
    
    public static function GetByName($name) {
        $name_field = static::getNameField() ;
        return static::getSingleResult(
                "select *"
                . " from `" . static::getTableName() . "`"
                . " where `" . $name_field . "` = :name",
                array("name" => $name)
            ) ;

    }
    
}