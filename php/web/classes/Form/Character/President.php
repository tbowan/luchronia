<?php

namespace Form\Character ;

class President extends \Quantyl\Form\Model\Radio {
    
    private $_city ;
    
    public function __construct(\Model\Game\City $city, $label = null, $mandatory = false, $description = null) {
        $this->_city = $city ;
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
        
        foreach (\Model\Game\Character::GetFromCitizenship($this->_city) as $citizen) {
            $this->addCharacter($choices, $citizen) ;
        }
        
        return $choices ;
    }
    
}
