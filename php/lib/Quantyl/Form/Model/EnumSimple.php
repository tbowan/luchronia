<?php

namespace Quantyl\Form\Model ;

class EnumSimple extends \Quantyl\Form\Fields\Radio {
    
    private $_table ;
    
    public function __construct(
            \Quantyl\Dao\BddTable $table,
            $label = null,
            $mandatory = true,
            $description = null) {

        $this->_table = $table ;

        parent::__construct($label, $mandatory, $description);
        
        $choices = array() ;
        foreach ($this->_table->GetAll() as $item) {
            $choices[$item->getId()] = $item ;
        }
        
        $this->setChoices($this->initChoice()) ;
        
    }
    
    public function initChoice() {
        $choices = array() ;
        foreach ($this->_table->GetAll() as $item) {
            $choices[$item->getId()] = $item ;
        }
        return $choices ;
    }
    
    public function parse($value) {
        try {
            $id = parent::parse($value) ;
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
