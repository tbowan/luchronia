<?php

namespace Form ;

class MyCharacter extends \Quantyl\Form\Model\Select {
    
    private $_user ;
    
    public function __construct(\Model\Identity\User $me, $label = null, $mandatory = false, $description = null) {
        $this->_user = $me ;
        parent::__construct(\Model\Game\Character::getBddTable(), $label, $mandatory, $description);
    }
    
    public function initChoices() {
        
        $choices = array() ;
        if (! $this->isMandatory()) {
            $choices[0] = \I18n::NONE() ;
        }
        
        foreach (\Model\Game\Character::GetFromUser($this->_user) as $c) {
            $choices[$c->id] = $c->getName() ;
        }
        
        return $choices ;
        
    }
    
}
