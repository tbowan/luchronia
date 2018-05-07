<?php

namespace Quantyl\Form\Model ;

class Checkbox extends \Quantyl\Form\Multiple {
    
    private $_table ;
    
    public function __construct(
            \Quantyl\Dao\BddTable $table,
            $label = null,
            $mandatory = false,
            $description = null) {
        
        $this->_table = $table ;
        parent::__construct($label, $mandatory, $description);
        
        $this->setChoices($this->initChoices()) ;
    }
    
    public function initChoices() {
        $objects = $this->_table->GetAll() ;
        $choices = array() ;
        if (! $this->isMandatory()) {
            $choices[0] = \I18n::NONE() ;
        }
        foreach ($objects as $o) {
            $choices[$o->getId()] = $o->getName() ;
        }
        
        return $choices ;
    }
    
    public function parse($values) {
        $ids = parent::parse($values) ;
        $res = array() ;
        foreach ($ids as $id) {
            $res[] = $this->_table->GetById($id) ;
        }
        return $res ;
    }
    
    public function unparse($values) {
        $ids = array() ;
        foreach ($values as $obj) {
            $ids[] = $obj->getId() ;
        }
        return parent::unparse($ids);
    }
    
}