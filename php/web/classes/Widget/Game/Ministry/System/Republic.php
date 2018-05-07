<?php

namespace Widget\Game\Ministry\System ;

class Republic extends Base {
    
    public function getSpecific() {
        
        $republic = \Model\Game\Politic\Republic::GetFromSystem($this->_system) ;
        
        $res  = "<h3>" . ucfirst(\I18n::REPUBLIC()) . " :</h3>" ;
        $res .= $this->_system->type->getDescription() ;
        
        $res .= \I18n::REPUBLIC_SPECIFIC_MESSAGE(
                $republic->duration,
                $republic->pres_delay,
                $republic->pres_type->getName(),
                $republic->pres_point,
                $republic->sys_delay,
                100 * $republic->sys_quorum,
                100 * $republic->sys_threshold
                ) ;
        
        $pres = \Model\Game\Politic\President::GetLastFromRepublic($republic) ;
        if ($res == null) {
            $res .= \I18n::REPUBLIC_WITHOUT_PRESIDENT() ;
        } else {
            $res .= \I18n::CURRENT_PRESIDENT(
                    $pres->character->id,
                    $pres->character->getName()) ;
        }
                
        return $res ;
        
    }
    
}
