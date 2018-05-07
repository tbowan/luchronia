<?php

namespace Answer\View\Game\Ministry ;

class Homeland extends Base {

    public function getCityzenship($classes = "") {
        $res = "" ;
        $res .= \I18n::CITIZENSHIP_MESSAGE() ;
        
        $res .= "<ul>" ;
        $res .= "<li><strong>" . \I18n::CITIZEN() . " :</strong> " 
                . \Model\Game\Character::CountCitizen($this->_city) . " " 
                . new \Quantyl\XML\Html\A("/Game/City/Citizen?city={$this->_city->id}", \I18n::SEE())
                . "</li>" ;
        $res .= "<li><strong>" . \I18n::CITIZENSHIP_MODE() . " :</strong> " 
                . $this->_city->citizenship->getName()
                . "</li>" ;
        $res .= "<li><strong>" . \I18n::YOUR_CITIZENSHIP() . " :</strong> "
                . $this->getYourCitizenship()
                . "</li>" ;
        $res .= "</ul>" ;
        
        if ($this->_isadmin) {
            $more = new \Quantyl\XML\Html\A("/Game/Ministry/Homeland/Citizenship?city={$this->_city->id}", \I18n::MANAGE()) ;
        } else {
            $more = "" ;
        }
        
        return new \Answer\Widget\Misc\Section(\I18n::CITIZENSHIP(), $more, "", $res, $classes) ;

    }
    
    public function getYourCitizenship() {
        if ($this->_character->isCitizen($this->_city)) {
            return \I18n::CITIZENSHIP_ALREADY() ;
        } else if (\Model\Game\Citizenship::HasPending($this->_character, $this->_city)) {
            return \I18n::CITIZENSHIP_HASPENDING() ;
        } else if ($this->_city->citizenship->equals(\Model\Enums\Citizenship::GetByName("CLOSED"))) {
            return \I18n::CITIZENSHIP_CLOSED() ;
        } else {
            return \I18n::NOTYET_CITIZEN($this->_city->id) ;
        }
    }
    
    public function getTax($classes = "") {
        
        $tax = \Model\Game\Tax\Stranger::GetFromCity($this->_city) ;
        $res = "" ;
        $res .= \I18n::HOMELAND_TAX_MESSAGE(
                $tax->fix,
                $tax->var
                ) ;
                
        if ($this->_isadmin) {
            $res .= ""
                    . "<p>"
                    . new \Quantyl\XML\Html\A("/Game/Ministry/Tax/ChangeStranger?city={$this->_city->id}", \I18n::CHANGE_TAX())
                    . ".</p>" ;
        }
        
        return new \Answer\Widget\Misc\Section(\I18n::TAX(), "", "", $res, $classes) ;
    }
    
    public function getWall($classes = "") {
        $res = "" ;
        $wall = \Model\Game\Building\Wall::GetFromCity($this->_city) ;
        if ($wall == null) {
            $res .= \I18n::HOMELAND_NOWALL() ;
        } else {
            $res .= \I18n::HOMELAND_WALL_FORALL($wall->door->getName()) ;
            if ($this->_isadmin) {
                $res .= \I18n::HOMELAND_WALL_FORADMIN($wall->id) ;
            }
        }
        
        return new \Answer\Widget\Misc\Section(\I18n::CITYWALL(), "", "", $res, $classes) ;
    }
    
    public function getSpecific() {
        
        return ""
                . $this->getCityzenship()
                . $this->getTax()
                . $this->getWall() ;
    }
    
}
