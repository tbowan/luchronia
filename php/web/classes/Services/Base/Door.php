<?php

namespace Services\Base ;

abstract class Door extends Character {
    
    public function checkPermission(\Quantyl\Request\Request $req) {
        parent::checkPermission($req) ;
        
        $city = $this->getCity() ;
        if (! $city->canEnter($this->getCharacter()) ) {
            throw new \Quantyl\Exception\Http\ClientForbidden(\I18n::EXP_WALL_AVOIDED()) ;
        }
    }
    
    public abstract function getCity() ;
    
}
