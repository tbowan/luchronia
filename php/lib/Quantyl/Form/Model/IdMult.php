<?php

namespace Quantyl\Form\Model ;

class IdMult extends \Quantyl\Form\Multiple {
    
    private $_table ;
    
    public function __construct(
            \Quantyl\Dao\BddTable $table,
            $label = null,
            $mandatory = true,
            $description = null) {
        
        $this->_table = $table ;
        parent::__construct($label, $mandatory, $description) ;
        
    }
    
    public function isChosable($value) {
        return true ;
    }
    
    public function parse($value) {
        $values = parent::parse($value) ;
        
        $res = array() ;
        foreach ($values as $id) {
            try {
                $res[$id] = $this->_table->GetById($id) ;
            } catch (\Exception $e) {
                if ($this->isMandatory()) {
                    throw new \Exception() ;
                }
            }
        }
        return $res ;
    }
    
    public function unparse($value) {
        $res = array() ;
        foreach ($value as $obj) {
            $id = $obj->getId() ;
            $res[$id] = $id ;
        }
        return parent::unparse($res) ;
    }
}
