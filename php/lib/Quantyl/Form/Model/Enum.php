<?php

namespace Quantyl\Form\Model ;

class Enum extends \Quantyl\Form\Choice {
    
    private $_table ;
    
    public function __construct(
            \Quantyl\Dao\BddTable $table,
            $label = null,
            $mandatory = true,
            $description = null) {

        parent::__construct($label, $mandatory, $description);
        $this->_table = $table ;
        
        $choices = array() ;
        foreach ($this->_table->GetAll() as $item) {
            $choices[$item->getId()] = $item ;
        }
        
        $this->setChoices($choices) ;
        
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

    public function getInputHTML($key, $value) {
        $basedir = "/Media/icones/" . 
                    str_replace("\\", "/", $this->_table->getClassName()) ;
        
        $res = "\n" ;
        $res .= "<div class=\"form_enum\">\n" ;
        foreach ($this->getChoices() as $k => $item) {
            if ($k == $value) {
                $checked = " checked=\"\"" ;
            } else {
                $checked = "" ;
            }
            $res .= "\t<div class=\"choice\">\n" ;
            $res .= "\t\t<input"
                    . " type=\"radio\""
                    . " id=\"" . $key . "-" . $k . "\""
                    . " name=\"" . $key . "\""
                    . " value=\"" . $k . "\""
                    . $checked
                    . " />\n" ;
            $res .= "\t\t<label"
                    . " for=\"" . $key . "-" . $k . "\""
                    . ">" ;
            
            $v = $item->getValue() ;
            $n = $item->getName() ;
            
            $res .= "<div class=\"illustration\">" ;
            $res .= "<img class=\"unselected\" src=\"$basedir/$v.png\" alt=\"$n\"/>" ;
            $res .= "<img class=\"selected\"   src=\"$basedir/$v-selected.png\" alt=\"$n\"/>" ;
            $res .= "</div>" ;
            
            $res .= "<div class=\"name\">" ;
            $res .= $n ;
            $res .= "</div>" ;
            
            $res .= "<div class=\"description\">" ;
            $res .= $item->getDescription() ;
            $res .= "</div>" ;
            
            
            $res .= "</label>\n" ;
            $res .= "\t</div>\n" ;
        }
        $res .= "</div>" ;
        return $res ;
    }

}
