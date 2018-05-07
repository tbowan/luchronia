<?php

namespace Widget\Game\Ministry\System ;

class Democracy extends Base {
    
    public function getSpecific() {
        
        $democracy = \Model\Game\Politic\Democracy::GetFromSystem($this->_system) ;
        
        $res  = "<h3>" . ucfirst(\I18n::DEMOCRACY()) . " :</h3>" ;
        $res .= $this->_system->type->getDescription() ;
        
        $res .= \I18n::SENATE_SPECIFIC_MESSAGE(
                $democracy->gov_delay,
                100 * $democracy->gov_quorum,
                100 * $democracy->gov_threshold,
                $democracy->sys_delay,
                100 * $democracy->sys_quorum,
                100 * $democracy->sys_threshold
                ) ;
        
        return $res ;
        
    }
    
}
