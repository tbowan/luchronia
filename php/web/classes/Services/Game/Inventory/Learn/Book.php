<?php

namespace Services\Game\Inventory\Learn ;

class Book extends Base {
    
    private $_book ;
    
    public function getBook() {
        if (! isset($this->_book)) {
            $this->_book = \Model\Game\Ressource\Book::GetByItem($this->inventory->item) ;
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
        
        $b = $this->getBook() ;
        $learn = $this->getCs() ;
        $point = $learn->level ;
        $form->addMessage(\I18n::LEARN_BOOK_MESSAGE($point, $b->skill->getName())) ;
        
        $form->addInput("tool", new \Form\Tool\Learn($this->getCs(), $this->getComplete())) ;
    }
    
    public function proceedLearning() {
        $char  = $this->getCharacter() ;
        $book  = $this->getBook() ;
        $learn = $this->getCs() ;
        $point = $learn->level ;
        
        \Model\Game\Skill\Character::LearnFromCharacterAndSkill($char, $book->skill, $point) ;
        
        $this->inventory->amount -= 0.01 ;
        $this->inventory->update() ;
        
        return \I18n::LEARNING_SKILL_POINTS($point, $book->skill->getName()) ;
   }

}