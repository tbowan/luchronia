<?php

namespace Answer\Widget\Game\Ministry\Dgap ;

class Revolution extends \Answer\Widget\Misc\Section {
    
    public function __construct(\Model\Game\Politic\System $system, \Model\Game\Character $viewer, $classes = "") {
        
        $more = "" ;
        if ($viewer->isCitizen($system->city)) {
            $more .= new \Quantyl\XML\Html\A("/Game/Ministry/System/Change/Revolt?city={$system->city->id}", \I18n::CREATE()) ;
            $more .= ", " ;
            $more .= new \Quantyl\XML\Html\A("/Game/Ministry/System/Find", \I18n::FIND_REVOLUTION()) ;
        }
        
        parent::__construct(\I18n::DGAP_OPPOSITION(), $more, "", $this->_getRevolution($system, $viewer), $classes);
    }
    
    private function _getRevolution(\Model\Game\Politic\System $system, \Model\Game\Character $viewer) {
        
        $support = \Model\Game\Politic\Support::GetFromCharacterAndCity($viewer, $system->city) ;
        
        if ($support === null || $support->revolution->isEnded()) {
            return \I18n::SUPPORT_NO_REVOLUTION() ;
        } else {
            $revolution = $support->revolution ;
            $target     = $revolution->proposed ;
            $support    = \Model\Game\Politic\Support::CountFromRevolution($revolution) ;
            $citizen    = \Model\Game\Character::CountUnlockedCitizen($system->city) ;
            $high       = ceil(2 * $citizen / 3.0) ;
            
            $name = $target->type->getName() . ($target->name != "" ? " : {$target->name}" : "") ;
            
            $res  = "" ;
            $res .= \I18n::SUPPORT_THIS_REVOLUTION() ;
            $res .= "<ul class=\"itemList\">" ;
            $res .= "<li><div class=\"item\">" ;
            $res .= "<div class=\"icon\">" . $target->type->getImage() . "</div>" ;
            $res .= "<div class=\"description\">" ;
                $res .= "<div class=\"name\">" . $name . "</div>" ;
                $res .= "<div>" . \I18n::SECRET_ID() . " : " . $revolution->secretid . "</div>" ;
                $res .= "<div>" . \I18n::SUPPORT() . " : $support / $high " . new \Quantyl\XML\Html\Meter(0, $high, $support) . "</div>"  ;
                $res .= "<div class=\"links\">"
                        . new \Quantyl\XML\Html\A("/Game/Ministry/System/UnSupport?revolution={$revolution->id}", \I18n::DOUNSUPPORT())
                        . ", "
                        . new \Quantyl\XML\Html\A("/Game/Ministry/System?system={$target->id}", \I18n::DETAILS())
                        . "</div>" ;
            $res .= "</div>" ;
            $res .= "</div></li>" ;
            $res .= "</ul>" ;
            return $res ;
        }
        
    }
    
}
