<?php

namespace Services\Game\Skill\Indoor ;

use Model\Game\Ressource\Inventory;

class Primary extends Base {
    
    private $_primary ;
    
    public function init() {
        parent::init();
        $this->_primary = \Model\Game\Skill\Primary::GetFromSkill($this->cs->skill) ;
    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        parent::fillDataForm($form);
    }

    public function getToolInput() {
        return new \Form\Tool\Primary($this->cs, $this->getComplete(), $this->inst) ;
    }
    
    public function getAmount($munition) {
        $natural       = \Model\Game\Ressource\Natural::GetFromCityAndItem($this->inst->city, $this->_primary->in) ;
        return parent::getAmount($munition) * round(
                $this->cs->level
                * $this->inst->level
                * $natural->coef
                * $this->_primary->coef
                , 2);
    }
    
    public function getTaxableAmount($munition) {
        return $this->getAmount($munition) / $this->_primary->coef ;
    }
    
    public function doStuff($final_coef, $data) {
        
        $item       = $this->_primary->out ;
        $msg = \I18n::SKILL_PRIMARY_NORMAL($final_coef, $item->getName()) ;

        if ($final_coef != 0) {
            $rest = Inventory::AddItem($this->cs->character, $item, $final_coef) ;
            if ($rest > 0) {
                $me = $this->getCharacter() ;
                $city = $me->position ;
                \Model\Game\Ressource\City::GiveToCity($city, $item, $rest, $me) ;
                $msg .= \I18n::INVENTORY_FULL_GIVE_CITY(
                        $rest, $item->getName(),
                        $city->id, $city->id, $city->getName()
                        ) ;
            }
        } else {
            throw new \Exception(\I18n::EXP_NATURAL_EXHAUSTED()) ;
        }
        $msg .= parent::doStuff($final_coef, $data) ;
        return $msg ;
    }

}
