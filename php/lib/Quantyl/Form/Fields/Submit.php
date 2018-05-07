<?php

namespace Quantyl\Form\Fields ;

class Submit implements \Quantyl\Form\Input {

    private $_label   ;
    private $_clicked ;
    private $_disabled ;
    private $_needvalid ;
    private $_callback_obj ;
    private $_callback_method ;
    
    private $_isvalid ;
    
    public function __construct($label, $needvalidform = true, $enabled = true) {
        $this->_label     = $label ;
        $this->_clicked   = false ;
        $this->_disabled  = ! $enabled ;
        $this->_needvalid = $needvalidform ;
        $this->_isvalid   = true ;
    }

    public function setCallBack($obj, $methodname) {
        $this->_callback_obj = $obj ;
        $this->_callback_method = $methodname ;
    }
    
    public function callBack($data, $form) {
        $obj = $this->_callback_obj ;
        $methodname = $this->_callback_method ;
        return $obj->$methodname($data, $form) ;
    }
    
    
    public function needValidForm() {
        return $this->_needvalid ;
    }
    
    public function parseValue($value) {
        if ($value === null) {
            $this->_clicked = false ;
        } else if ($this->_disabled) {
            // Can not click on this button
            $this->_isvalid = false ;
        } else if ($this->_label === $value) {
            $this->_clicked = true ;
        } else {
            // WTF ?
            $this->_isvalid = false ;
        }
    }
    
    public function setValue($value) {
        if (! is_bool($value)) {
            // only boolean accepted
            throw new \Exception() ;
        } else if ($this->_disabled && $value) {
            // Can not click on this button
            throw new \Exception() ;
        } else {
            $this->_clicked = $value ;
        }
    }
    
    public function getValue() {
        return $this->_clicked ;
    }
    
    public function isValid() {
        return $this->isValid() ;
    }
    
    public function getHTML($key = null) {
        $input = "<input"
                . " type=\"submit\""
                . " name=\"$key\""
                . " value=\"" . htmlspecialchars($this->_label, ENT_QUOTES, "UTF-8") . "\"" ;
        
        if($this->_disabled) {
            $input .= " disabled=\"\"" ;
        }
        
        $input .= " />\n" ;
        
        return $input ;
    }

}
