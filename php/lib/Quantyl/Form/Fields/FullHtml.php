<?php

namespace Quantyl\Form\Fields ;

require_once 'HTMLPurifier.auto.php' ;

class FullHtml extends \Quantyl\Form\Field {
    
    private $_tinymce ;
    
    public function __construct($label = null, $mandatory = false, $description = null) {
        parent::__construct($label, $mandatory, $description);
        $this->_tinymce = true ;
    }
    
    public function setTinyMCE($bool) {
        $this->_tinymce = $bool ;
        return $this ;
    }
    
    public function parse($value) {
        $dirty_html = parent::parse($value);
        
        $config     = \HTMLPurifier_Config::createDefault();
        $config->set("Core.CollectErrors", true) ;
        $purifier   = new \HTMLPurifier($config);
        $clean_html = $purifier->purify($dirty_html);        
        
        return $clean_html ;
    }
    
    public function getInputHTML($key, $value) {
        $res = "" ;
        if ($this->_tinymce) {
            $res .= "<textarea class=\"htmlarea\" name=\"$key\">";
        } else {
            $res .= "<textarea name=\"$key\">";
        }
        $res .= htmlspecialchars($value, ENT_QUOTES, "UTF-8") ;
        $res .= "</textarea>";
        return $res;
    }
    
}
