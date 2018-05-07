<?php

namespace Form\Character ;

class Friend extends \Quantyl\Form\Model\Radio{

    private $_me ;
    
    public function __construct(\Model\Game\Character $me, $label = null, $mandatory = false, $description = null) {
        $this->_me = $me ;
        parent::__construct(\Model\Game\Character::getBddTable(), $label, $mandatory, $description);
    }
    
    private function addCharacter(&$table, \Model\Game\Character $char) {
        $table[$char->id] = ""
                    . new \Quantyl\XML\Html\A("/Game/Character/Show?id={$char->id}", $char->getName())
                        . $char->race->getName() . " "
                        . $char->sex->getName() . " - "
                        . \I18n::LEVEL() . " " . $char->level . " " ;
    }
    
    public function initChoices() {
        $choices = array() ;
        
        $this->addCharacter($choices, $this->_me) ;
        
        foreach (\Model\Game\Social\Friend::GetFromA($this->_me) as $friend) {
            $this->addCharacter($choices, $friend->b) ;
        }
        
        return $choices ;
    }
    
}
