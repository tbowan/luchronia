<?php

namespace Services\Game\Skill\Outdoor ;

class Fight extends Base {

    public function fillDataForm(\Quantyl\Form\Form &$form) {
        parent::fillDataForm($form);
    }

    public function getToolInput() {
        return new \Form\Tool\Fight($this->cs, $this->getComplete()) ;
    }
    
    public function getAmount($munition) {
        return parent::getAmount($munition) * $this->cs->level * (1 + 4 * $this->cs->skill->cost) ;
    }
    
    public function doStuff($amount, $data) {
        
        $city = $this->getCity() ;
        $dammages = min($amount, $city->monster_out) ;
        $city->monster_out -= $dammages ;
        $city->fitness     -= $dammages ;
        $city->update() ;
        
        $msg  = \I18n::FIGHT_MESSAGE($this->cs->skill->getName(), $dammages) ;
        $msg .= parent::doStuff($amount, $data) ;
        
        return $msg ;
    }

}
