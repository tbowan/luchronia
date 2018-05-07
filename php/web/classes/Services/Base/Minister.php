<?php

namespace Services\Base ;

abstract class Minister extends Character {
    
    public function checkPermission(\Quantyl\Request\Request $req) {
        parent::checkPermission($req);
        $city = $this->getCity() ;
        $char = $this->getCharacter() ;
        $mini = $this->getMinistry() ;
        
        if (! \Model\Game\Politic\Minister::hasPower($char, $city, $mini)) {
            throw new \Quantyl\Exception\Http\ClientForbidden(\I18n::EXP_NEED_MINISTER($mini->getName())) ;
        }
    }
    
    public abstract function getCity() ;
    
    public abstract function getMinistry() ;
    
}
