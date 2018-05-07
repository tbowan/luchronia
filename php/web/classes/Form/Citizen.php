<?php

namespace Form ;

class Citizen extends \Quantyl\Form\Model\Radio {
    
    private $_city ;
    
    public function __construct(\Model\Game\City $city, $label = null, $mandatory = false, $description = null) {
        $this->_city = $city ;
        parent::__construct(\Model\Game\Character::getBddTable(), $label, $mandatory, $description);
    }
    
    public function initChoices() {
        $choices = array() ;
        foreach (\Model\Game\Character::GetFromCitizenship($this->_city) as $c) {
            $choices[$c->id] = $c->getName() . " " . new \Quantyl\XML\Html\A("/Game/Character/Show?id={$c->id}", \I18n::SEE()) ;
        }
        return $choices ;
    }
    
}
