<?php

namespace Answer\View\Game\Ministry ;

class Health extends Base {

    public function getRepatriate($classes = "") {
        $res = "" ;
        
        if ($this->_city->repatriate_allowed) {
            $res .= \I18n::REPATRIATE_COST($this->_city->repatriate_cost) ;
        } else {
            $res .= \I18n::REPATRIATE_FORBIDEN() ;
        }
        
        if ($this->_isadmin) {
            $res .= \I18n::REPATRIATE_FOR_ADMIN($this->_city->id) ;
        }
        
        return new \Answer\Widget\Misc\Section(\I18n::MINISTRY_REPATRIATION(), "", "", $res, $classes) ;
    }
    
    public function getRefuel($classes = "") {
        $res = "" ;
        
        if ($this->_city->equals($this->_character->position)) {
            $res .= \I18n::MINISTRY_REFUEL_ALLOWED() ;
        } else {
            $res .= \I18n::MINISTRY_REFUEL_DISALLOWED() ;
        }
        
        return new \Answer\Widget\Misc\Section(\I18n::MINISTRY_REFUEL(), "", "", $res, $classes) ;
    }
    
    public function getSpecific() {
        return ""
                . $this->getRepatriate()
                . $this->getRefuel() ;
    }
    
}