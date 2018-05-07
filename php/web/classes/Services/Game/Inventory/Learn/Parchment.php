<?php

namespace Services\Game\Inventory\Learn ;

class Parchment extends Base {
    
    private $_parchment ;
    
    public function getParchment() {
        if (! isset($this->_parchment)) {
            $this->_parchment = \Model\Game\Ressource\Parchment::GetByItem($this->inventory->item) ;
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

    public function fillDataForm(\Quantyl\Form\Form &$form) {
        parent::fillDataForm($form);
        
        $p = $this->getParchment() ;
        $form->addMessage(\I18n::LEARN_PARCHMENT_MESSAGE($p->skill->getName())) ;
        $form->addInput("tool", new \Form\Tool\Parchment($this->getCs(), $this->getComplete())) ;
    }


    public function proceedLearning() {
        $char = $this->getCharacter() ;
        $parchment = $this->getParchment() ;
        
        \Model\Game\Skill\Character::LearnFromCharacterAndSkill($char, $parchment->skill, 100) ;
        
        $this->inventory->amount -= 1 ;
        $this->inventory->update() ;
        
        return \I18n::LEARNING_NEW_SKILL_AVAILABLE($parchment->skill->getName()) ;
    }

}