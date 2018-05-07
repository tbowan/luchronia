<?php

namespace Model\Game\Politic ;

class Change extends \Quantyl\Dao\BddObject {
    
    // Parser
    
    public static function FromBddValue($name, $value) {
        switch($name) {
            case "question" :
                return Question::GetById($value) ;
            case "government" :
                return Government::GetById($value) ;
            default :
                return $value ;
        }
    }
    
    public static function ToBddValue($name, $value) {
        switch($name) {
            case "question" :
            case "government" :
                return $value->getId() ;
            default :
                return $value ;
        }
    }

}
