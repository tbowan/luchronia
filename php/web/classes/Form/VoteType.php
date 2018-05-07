<?php

namespace Form ;

class VoteType extends \Quantyl\Form\Model\Select {
    
    public function __construct($label = null, $mandatory = false, $description = null) {
        parent::__construct(\Model\Game\Politic\VoteType::getBddTable(), $label, $mandatory, $description);
    }
    
    public function initChoices() {
        $choices = parent::initChoices();
        unset($choices[\Model\Game\Politic\VoteType::System()->getId()]) ;
        return $choices ;
    }
    
}
