<?php

namespace Form ;

class Teachable extends \Quantyl\Form\Model\Select {
    
    private $_c ;
    private $_teacher ;
    private $_student ;
    
    public function __construct(
            \Model\Game\Characteristic $carac,
            \Model\Game\Character $teacher,
            \Model\Game\Character $student,
            $label = null, $mandatory = false, $description = null) {
        
        $this->_c = $carac ;
        $this->_teacher = $teacher ;
        $this->_student = $student ;
        
        parent::__construct(\Model\Game\Skill\Character::getBddTable(), $label, $mandatory, $description);
    }
    
    public function initChoices() {
        $choices = array() ;
        foreach (\Model\Game\Skill\Character::GetTeachable($this->_teacher, $this->_student) as $cs) {
            if ($this->_c->equals($cs->skill->characteristic)) {
                $choices[$cs->id] = $cs->skill->getName() ;
            }
        }
        return $choices ;
    }
    
}
