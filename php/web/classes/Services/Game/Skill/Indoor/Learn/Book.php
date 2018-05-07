<?php

namespace Services\Game\Skill\Indoor\Learn ;

class Book extends Base {
     
    private $_book ;
    
    public function getBook() {
        if (! isset($this->_book)) {
            $this->_book = \Model\Game\Ressource\Book::GetByItem($this->stock->item) ;
        }
        return $this->_book ;
    }
    
     public function checkPermission(\Quantyl\Request\Request $req) {
        parent::checkPermission($req);
        
        if ($this->getBook() == null) {
            throw new \Quantyl\Exception\Http\ClientForbidden(\I18n::EXP_NOT_BOOK()) ;
        }
    }
    
    public function getCharacteristic() {
        $b = $this->getBook() ;
        return $b->skill->characteristic ;
    }
    
    public function fillDataForm(\Quantyl\Form\Form &$form) {
        parent::fillDataForm($form);
        $form->addMessage(\I18n::SKILL_LEARN_BOOK_MESSAGE($this->stock->item->getName(), $this->getBook()->skill->getName())) ;
    }

    public function proceedLearning($amount) {
        $skill = $this->getBook()->skill ;
        
        $points = $amount ;
        
        $cs = \Model\Game\Skill\Character::LearnFromCharacterAndSkill($this->getCharacter(), $skill, $points) ;
        $msg = \I18n::SKILL_LEARN_BOOK_DONE($points, $skill->getName()) ;
        
        $this->stock->amount -= 0.001 ;
        $this->stock->update() ;
        
        return $msg ;
    }

    public function getAmount($munition) {
        return parent::getAmount($munition) * $this->cs->level ;
    }
}
