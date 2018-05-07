<?php

namespace Form ;

class Target extends \Quantyl\Form\Model\Radio {
    
    private $_me ;
    private $_reflexive ;
    
    public function __construct(\Model\Game\Character $me, $reflexive, $label = null, $mandatory = false, $description = null) {
        $this->_me = $me ;
        $this->_reflexive = $reflexive ;
        parent::__construct(\Model\Game\Character::getBddTable(), $label, $mandatory, $description);
    }
    
    public function initChoices() {
        $choices = array() ;
        foreach (\Model\Game\Character::GetPopulation($this->_me->position) as $c) {
            if (! $c->equals($this->_me) || $this->_reflexive) {
                $choices[$c->id] = $c->getName() . " " . new \Quantyl\XML\Html\A("/Game/Character/Show?id={$c->id}", \I18n::SEE()) ;
            }
        }
        return $choices ;
    }
    
}
