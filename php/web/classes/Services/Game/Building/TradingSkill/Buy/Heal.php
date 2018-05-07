<?php

namespace Services\Game\Building\TradingSkill\Buy ;

class Heal extends Base {
    
    private $_heal_skill ;
    
    public function init() {
        parent::init();
        
        $this->_heal_skill = \Model\Game\Skill\Heal::GetFromSkill($this->sell->skill) ;
    }
    
    public function checkPermission(\Quantyl\Request\Request $req) {
        parent::checkPermission($req);
        
        // Must be same race
        $race = $this->_heal_skill->race ;
        if (! $race->equals($this->getCharacter()->race)) {
            throw new \Quantyl\Exception\Http\ClientForbidden(\I18n::EXP_MUST_BE_SAME_RACE($race->getName())) ;
        }
    }
    
    public function specificForm(\Quantyl\Form\Form &$form) {
        $form->addMessage(\I18n::BUY_TRADINGSKILL_HEAL_MESSAGE()) ;
    }
    
    
    public function specificProceed($data) {
        
        $me = $this->getCharacter() ;
        $me->heal($data["amount"] * 100) ;
        $me->update() ;
        
        
    }
    
}
