<?php

namespace Quantyl\Form ;

class FieldSet implements Input {
    
    private $_inputs ;
    private $_label ;
    private $_message ;
    
    public function __construct($label = null) {
        $this->_inputs = array() ;
        $this->_label  = $label ;
        $this->_message = null ;
    }
    
    public function addInput($key, Input $input) {
        $this->_inputs[$key] = $input ;
        return $input ;
    }
    
    public function getInput($key) {
        return $this->_inputs[$key] ;
    }
    
    public function countInputs() {
        return count($this->_inputs) ;
    }
    
    public function setMessage($msg) {
        $this->_message = $msg ;
    }
    
    public function addMessage($msg) {
        $this->_message .= $msg ;
    }
    
    public function getHTML($key = null) {
        
        $inner = "" ;
        foreach ($this->_inputs as $id => $input) {
            $name = $key === null ? $id : "{$key}[{$id}]" ;
            $inner .= $input->getHTML($name) ;
        }
        
        if ($this->_label != null) {
            return "<fieldset>\n"
                    . "<legend>{$this->_label}</legend>\n"
                    . "{$this->_message}"
                    . "$inner\n"
                    . "</fieldset>\n" ;
        } else {
            return $inner ;
        }
        
    }

    public function getValue() {
        $res = array() ;
        foreach ($this->_inputs as $key => $i) {
            $res[$key] = $i->getValue() ;
        }
        return $res ;
    }

    public function parseValue($value) {
        if (is_array($value)) {
            foreach ($this->_inputs as $key => $input) {
                $v = isset($value[$key]) ? $value[$key] : null ;
                $input->parseValue($v) ;
            }
        }
    }
    
    public function isValid() {
        foreach ($this->_inputs as $key => $input) {
            if (! $input->isValid()) {
                return false ;
            }
        }
        return true ;
    }
    
    public function setValue($value) {
         return $this->parseValue($value) ;
    }

}
