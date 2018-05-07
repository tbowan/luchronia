<?php

namespace Model\Game\Trading ;

class Type extends \Quantyl\Dao\AbstractEnum {
    
    protected static $_enumeration = array (
        1 => "ToBuy",
        2 => "ToSell" ,
    ) ;
    
    public function getPrefix() {
        return "TRADING_TYPE_" ;
    }
    
    public function isBestMin() {
        switch($this->getValue()) {
            case 1 :
                return true ;
            case 2 :
                return false ;
        }
    }
    
}
