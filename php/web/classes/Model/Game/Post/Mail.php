<?php

namespace Model\Game\Post ;

class Mail extends \Quantyl\Dao\BddObject {
    
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "author" :
                return \Model\Game\Character::GetById($value) ;
            case "city" :
                return \Model\Game\City::GetById($value) ;
            default:
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "author" :
            case "city" :
                return $value->getId() ;
            default:
                return $value ;
        }
    }
    
}
