<?php

namespace Answer\View\Game\Ministry ;

class Defense extends Base {
    
    public function getSpecific($classes = "") {
        $res = ""  ;
        
        if ($this->_city->sun < 0) {
            $res .= \I18n::NIGHT_MESSAGE(
                        \I18n::_date_time($this->_city->sunrise - DT),
                        \I18n::_date_time($this->_city->sunset - DT)
                    ) ;
        } else {
            $res .= \I18n::DAY_MESSAGE(
                        \I18n::_date_time($this->_city->sunset - DT),
                        \I18n::_date_time($this->_city->sunrise - DT)
                    ) ;
        }
        
        return new \Answer\Widget\Misc\Section(\I18n::MONSTERS(), "", "", $res, $classes) ;
    }
    
}
