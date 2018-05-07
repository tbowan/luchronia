<?php

namespace Form ;

class Healable extends \Quantyl\Form\Model\Radio {
    
    private $_me ;
    private $_race ;
    private $_reflexive ;
    
    public function __construct(\Model\Game\Character $me, \Model\Enums\Race $race, $reflexive, $label = null, $mandatory = false, $description = null) {
        $this->_me = $me ;
        $this->_reflexive = $reflexive ;
        $this->_race = $race ;
        parent::__construct(\Model\Game\Character::getBddTable(), $label, $mandatory, $description);
    }
    
    public function initChoices() {
        $choices = array() ;
        foreach (\Model\Game\Character::GetPopulation($this->_me->position) as $c) {
            if ($this->_race->equals($c->race) && (! $c->equals($this->_me) || $this->_reflexive)) {
                $choices[$c->id] = $c->getName() . " " . new \Quantyl\XML\Html\A("/Game/Character/Show?id={$c->id}", \I18n::SEE()) ;
            }
        }
        return $choices ;
    }
    
}
