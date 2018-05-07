<?php

namespace Services\Game\Building\TradingSkill\Buy ;

class Teach extends Base {
    
    private $_learn_skill ;
    
    public function specificForm(\Quantyl\Form\Form &$form) {
        $teach = \Model\Game\Skill\Teach::GetFromSkill($this->sell->skill) ;
        $learn = \Model\Game\Skill\Learn::GetFromCharacteristic($teach->characteristic) ;
        $this->_learn_skill = \Model\Game\Skill\Character::GetFromCharacterAndSkill($this->getCharacter(), $learn->skill) ;
        
        $form->addMessage(\I18n::BUY_TRADINGSKILL_TEACH_MESSAGE($this->_learn_skill->level)) ;
        
        $form->addInput("skill", new \Form\Teachable($teach->characteristic, $this->sell->character, $this->getCharacter(), \I18n::SKILL(), true)) ;
    }
    
    
    public function specificProceed($data) {
        
        $amount = $this->_learn_skill->level * $data["amount"] ;
        $me = $this->getCharacter() ;
        
        \Model\Game\Skill\Character::LearnFromCharacterAndSkill($me, $data["skill"]->skill, $amount) ;
        
        
    }
    
}
