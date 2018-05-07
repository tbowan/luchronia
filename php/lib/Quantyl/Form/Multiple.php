<?php

namespace Quantyl\Form ;

abstract class Multiple extends Field {

    private $_choices ;
    
    public function setChoices($choices) {
        $this->_choices = $choices ;
    }
    
    public function getChoices() {
        return $this->_choices ;
    }
    
    public function isChosable($value) {
        return isset($this->_choices[$value]) ;
    }
    
    /*
     * Doit être surchargée pour définir les règles
     */
    public function parse($values) {
        if ($values == null && ! $this->isMandatory()) {
            return array() ;
        } else if (! is_array($values)) {
            throw new \Exception() ;
        } else {
            foreach ($values as $value) {
                if (! $this->isChosable($value)) {
                    throw new \Exception() ;
                }
            }
            if (count($values) == 0 && $this->isMandatory()) {
                throw new \Exception() ;
            }
            return $values ;
        }
    }
    
    /*
     * Doit être surchargée pour définir les règles
     */
    public function unparse($values) {
        if (! is_array($values)) {
            throw new \Exception() ;
        } else {
            foreach ($values as $value) {
                if (! $this->isChosable($value)) {
                    throw new \Exception() ;
                }
            }
            return $values ;
        }
    }
    
    public function getInputHTML($key, $values) {
        $checked = array() ;
        if ($values != null) {
            foreach ($values as $v) {
                $checked[$v] = true ;
            }
        }
        $res = "" ;
        $res .= "\t<ul class=\"QFormChoice\">\n" ;
        foreach ($this->_choices as $k => $label) {
            $res .= "<li>" ;
            $res .= "<input"
                    . " type=\"checkbox\""
                    . " name=\"{$key}[]\""
                    . " id=\"$key-$k\""
                    . " value=\"$k\""
                    . (isset($checked[$k]) ? " checked=\"\"" : "")
                    . "/>" ;
            $res .= "<label"
                    . " for=\"$key-$k\">"
                    . $label
                    . "</label>" ;
            $res .= "</li>" ;
        }
        $res .= "</ul>" ;
        
        return $res ;
    }
    
}
