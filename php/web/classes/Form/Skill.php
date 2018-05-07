<?php

namespace Form ;

class Skill extends \Quantyl\Form\Model\Select {
    
    private $_c ;
    private $_teacher ;
    
    public function __construct(
            \Model\Game\Characteristic $carac,
            \Model\Game\Character $teacher,
            $label = null, $mandatory = false, $description = null) {
        
        $this->_c = $carac ;
        $this->_teacher = $teacher ;
        
        parent::__construct(\Model\Game\Skill\Character::getBddTable(), $label, $mandatory, $description);
    }
    
    public function initChoices() {
        $choices = array() ;
        foreach (\Model\Game\Skill\Character::GetFromCharacter($this->_teacher) as $cs) {
            if ($this->_c->equals($cs->skill->characteristic)) {
                $choices[$cs->id] = $cs->skill->getName() ;
            }
        }
        return $choices ;
    }
    
}
