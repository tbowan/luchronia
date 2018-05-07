<?php

namespace Form\Tool ;

class FieldGather extends Base {
    
    private $_field ;
    
    public function __construct(\Model\Game\Skill\Character $cs, \Model\Game\Tax\Complete $tax, $field) {
        $this->_field = $field ;
        parent::__construct($cs, $tax);
    }
    
    public function getAmount($munition) {
        return parent::getAmount($munition) * $this->_cs->level * $this->_field->instance->level ;
    }
    
}
