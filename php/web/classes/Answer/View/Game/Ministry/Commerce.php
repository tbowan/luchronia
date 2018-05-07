<?php

namespace Answer\View\Game\Ministry ;

class Commerce extends Base {

    public function getTreasure($classes = "") {
        $res = "" ;
        
        $tax = \Model\Game\Tax\City::GetFromCity($this->_city) ;
        
        $res .= \I18n::TREASURE_MESSAGE(
                $this->_city->credits,
                $tax->fix,
                $tax->var
                ) ;
        
        $res .= "<ul>" ;
        $res .= "<li>" . new \Quantyl\XML\Html\A("/Game/Ministry/Commerce/Register?city={$this->_city->id}", \I18n::SEE_REGISTER()) . "</li>" ;
        $res .= "<li>" . new \Quantyl\XML\Html\A("/Game/Ministry/Commerce/GiveCredits?city={$this->_city->id}", \I18n::GIVE_CREDITS()) . "</li>" ;
        if ($this->_isadmin) {
            $res .= "<li>" . new \Quantyl\XML\Html\A("/Game/Ministry/Commerce/TakeCredits?city={$this->_city->id}", \I18n::TAKE_CREDITS()) . "</li>" ;
            $res .= "<li>" . new \Quantyl\XML\Html\A("/Game/Ministry/Tax/ChangeCity?city={$this->_city->id}", \I18n::CHANGE_TAX()) . "</li>" ;
            $res .= "<li>" . new \Quantyl\XML\Html\A("/Game/Ministry/Commerce/Stocks?city={$this->_city->id}", \I18n::MANAGE_STOCKS()) . "</li>" ;
        }
        $res .= "</ul>" ;
        return new \Answer\Widget\Misc\Section(\I18n::TREASURE(), "", "", $res, $classes) ;
    }
    
    public function getSpecific() {
        
        return ""
                . $this->getTreasure()
                ;
        
        
    }
    
}