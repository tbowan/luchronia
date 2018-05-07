<?php

namespace Answer\View\Game\Building ;

class Site extends Base {
    
    public function getSpecific() {
        
        $isminister = \Model\Game\Politic\Minister::hasPower($this->_character, $this->_instance->city, \Model\Game\Politic\Ministry::GetByName("Commerce")) ;
        
        return new \Answer\Widget\Game\Building\SiteNeed($this->_instance, $isminister) ;
    }
    
}
