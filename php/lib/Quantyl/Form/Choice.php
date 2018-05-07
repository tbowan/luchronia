<?php

namespace Quantyl\Form ;

abstract class Choice extends Field {

    private $_choices ;
    
    public function setChoices($choices) {
        $this->_choices = $choices ;
    }
    
    public function getChoices() {
        return $this->_choices ;
    }
    
    /*
     * Doit être surchargée pour définir les règles
     */
    public function parse($value) {
        if (isset($this->_choices[$value]) || ! $this->isMandatory()) {
            return $value ;
        } else {
            throw new \Exception() ;
        }
    }
    
    /*
     * Doit être surchargée pour définir les règles
     */
    public function unparse($value) {
        if (isset($this->_choices[$value])) {
            return $value ;
        } else if ($this->isMandatory ()) {
            throw new \Exception() ;
        }
    }
    
}
