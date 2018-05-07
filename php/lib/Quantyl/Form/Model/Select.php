<?php

namespace Quantyl\Form\Model ;

class Select extends \Quantyl\Form\Fields\Select {
    
    
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
    
    public function parse($value) {
        $id = parent::parse($value) ;
        try {
            return $this->_table->GetById($id) ;
        } catch (\Exception $ex) {
            if ($this->isMandatory()) {
                throw $ex ;
            } else {
                return null ;
            }
        }
        
    }
    
    public function unparse($value) {
        return ($value == null ? null : parent::unparse($value->getId()));
    }
    
}