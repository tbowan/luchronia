<?php

namespace Quantyl\Form ;

abstract class Field implements Input {
    
    private $_parsed ;
    private $_value ;
    private $_label ;
    private $_description ;
    private $_error ;
    private $_mandatory ;
    
    public function __construct($label = null, $mandatory = false, $description = null) {
        $this->_label       = $label ;
        $this->_description = $description ;
        $this->_value       = null ;
        $this->_parsed      = null ;
        $this->_error       = null ;
        $this->_mandatory   = $mandatory ;
    }
    
    public abstract function getInputHTML($key, $value) ;
    
    public function getHTML($key = "") {
        
        $class = "input" ;
        if ($this->_error !== null) {
            $class .= " error" ;
        }
        if ($this->_mandatory) {
            $class .= " mandatory" ;
        }
        
        $html = "<div class=\"$class\">\n" ;
        
        if ($this->_label !== null) {
            $html .= "\t<label for=\"$key\">" . $this->_label . "</label>\n" ;
        }
        $html .= "\t" . $this->getInputHTML($key, $this->_parsed) ;
        
        if ($this->_error !== null) {
            $html .= "\t<div class=\"error\">"
                    . "\t" . $this->_error
                    . "\t</div>\n" ;
        }
        
        if ($this->_description !== null) {
            $html .= "\t<div class=\"description\">"
                    . "\t" . $this->_description
                    . "\t</div>\n" ;
        }
        
        $html .= "</div>\n" ;
        return $html ;
    }

    public function getValue() {
        return $this->_value ;
    }

    public function isValid() {
        return $this->_error === null ;
    }
    
    /*
     * Doit être surchargée pour définir les règles
     */
    protected function parse($value) {
        return $value ;
    }
    
    public function isMandatory() {
        return $this->_mandatory ;
    }
    
    /*
     * Doit être surchargée pour définir les règles
     */
    protected function unparse($value) {
        return $value ;
    }
    
    public final function parseValue($value) {
        $this->_parsed = $value ;
        
        if ($this->_mandatory && $this->_parsed == null) {
            $this->_error = \I18n::EXP_FORM_VALUE_IS_MANDATORY() ;
        }
        
        try {
            $this->_value  = $this->parse($value) ;
        } catch (\Exception $ex) {
            $this->_value = null ;
            $this->_error = $ex->getMessage() ;
        }
    }
    
    public final function setValue($value) {
        $this->_value  = $value ;
        $this->_parsed = $this->unparse($value) ;
    }
    
}
