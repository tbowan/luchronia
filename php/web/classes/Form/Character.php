<?php

namespace Form ;

class Character extends \Quantyl\Form\FieldSet {
    
    public function __construct($label = null) {
        parent::__construct($label);
        
        $this->_search = new \Quantyl\Form\Fields\Text(\I18n::SEARCH_CHARACTER()) ;
        $this->_character = new \Quantyl\Form\Model\Radio(\Model\Game\Character::getBddTable(), \I18n::CHOSE_CHARACTER(), false) ;
        $this->_character->setChoices(array()) ;
        
        $this->addInput("search", $this->_search) ;
        $this->addInput("character", $this->_character) ;
    }
    
    public function getValue() {
        return $this->_character->getValue() ;
    }
    
    public function parseValue($value) {
        parent::parseValue($value) ;
        
        $searchterm = $this->_search->getValue() ;
        if ($searchterm != "") {
            $choices = array() ;
            foreach (\Model\Game\Character::GetBySearch($searchterm) as $char) {
                $choices[$char->id] =
                        new \Quantyl\XML\Html\A("/Game/Character/Show?id={$char->id}", $char->getName()) . " "
                        . $char->race->getName() . " "
                        . $char->sex->getName() . " - "
                        . \I18n::LEVEL() . " " . $char->level . " " ;
            }
            $this->_character->setChoices($choices) ;
        }
        
        $c = $this->_character->getValue() ;
        if ($c == null) {
            throw new \Exception() ;
        }
    }
    
    public function setValue($character) {
        $this->_character->setValue($character) ;
    }

}
