<?php

namespace Answer\View\Game\System ;

class Revolution extends Base {
    
    public function declaredContent(\Model\Game\Politic\Revolution $revolution) {
        $support = \Model\Game\Politic\Support::CountFromRevolution($revolution) ;
        $citizen = \Model\Game\Character::CountCitizen($this->_system->city) ;
        $target = ceil(2.0 * $citizen / 3.0) ;
        
        $res  = "<li><strong>" . \I18n::CURRENT_SUPPORT()   . " :</strong> " . $support . "</li>" ;
        $res .= "<li><strong>" . \I18n::TARGET_SUPPORT()    . " :</strong> " . $target . "</li>" ;
        $res .= "<li><strong>" . \I18n::REVOLUTION_UNTIL()       . " :</strong> " . \I18n::_date_time($this->_system->end) . "</li>" ;
        
        return $res ;
    }
    
    public function getSupport($revolution) {
        $mine = \Model\Game\Politic\Support::GetFromCharacter($this->_character) ;

        if ($mine == null || ! $mine->revolution->equals($revolution)) {
            // Can support
            return \I18n::REVOLUTION_CAN_SUPPORT($revolution->id) ;
        } else {
            // Ne plus supporter
            return \I18n::REVOLUTION_CAN_UNSUPPORT($revolution->id) ;
        }
    }
    
    public function getSpecific() {
        $revolution = \Model\Game\Politic\Revolution::GetFromSystem($this->_system) ;
        
        $res = "" ;
        $res .= new \Answer\Widget\Game\Ministry\Dgap\Support($this->_system, $this->_viewer, "card-1-2") ;
        return $res ;
        
    }

}
