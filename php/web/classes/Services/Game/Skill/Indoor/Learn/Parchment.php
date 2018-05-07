<?php

namespace Services\Game\Skill\Indoor\Learn ;

class Parchment extends Base {
     
    private $_parchment ;
    
    public function getParchment() {
        if (! isset($this->_parchment)) {
            $this->_parchment = \Model\Game\Ressource\Parchment::GetByItem($this->stock->item) ;
        }
        return $this->_parchment ;
    }
    
     public function checkPermission(\Quantyl\Request\Request $req) {
        parent::checkPermission($req);
        
        if ($this->getParchment() == null) {
            throw new \Quantyl\Exception\Http\ClientForbidden(\I18n::EXP_NOT_PARCHMENT()) ;
        }
        
        $skill = $this->getParchment()->skill ;
        $cs = \Model\Game\Skill\Character::GetFromCharacterAndSkill($this->getCharacter(), $skill) ;
        if ($cs != null && $cs->level > 0) {
            throw new \Quantyl\Exception\Http\ClientForbidden(\I18n::EXP_CANNOT_READ_KNOWN_SKILL()) ;
        }
    }
    
    public function getCharacteristic() {
        $p = $this->getParchment() ;
        return $p->skill->characteristic ;
    }

    public function getToolInput() {
        return new \Form\Tool\Parchment($this->cs, $this->getComplete());
    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        parent::fillDataForm($form);
        $form->addMessage(\I18n::SKILL_LEARN_PARCHMENT_MESSAGE($this->stock->item->getName(), $this->getParchment()->skill->getName())) ;
    }

    public function proceedLearning($amount) {
        $char = $this->getCharacter() ;
        $parchment = $this->getParchment() ;
        
        \Model\Game\Skill\Character::LearnFromCharacterAndSkill($char, $parchment->skill, 100) ;
        
        $this->stock->amount -= 0.01 ;
        $this->stock->update() ;
        
        return \I18n::LEARNING_NEW_SKILL_AVAILABLE($parchment->skill->getName()) ;
    }

}
