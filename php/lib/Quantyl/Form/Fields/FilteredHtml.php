<?php

namespace Quantyl\Form\Fields ;

require_once 'HTMLPurifier.auto.php' ;

class FilteredHtml extends \Quantyl\Form\Field {
    
    public function getInputHTML($key, $value) {
        $res  = "<textarea class=\"htmlarea\" name=\"$key\">";
        $res .= htmlspecialchars($value, ENT_QUOTES, "UTF-8") ;
        $res .= "</textarea>";
        return $res;
    }
    
    
    public function parse($value) {
        $dirty_html = parent::parse($value);
        
        $config     = \HTMLPurifier_Config::createDefault();
        $config->set("Core.CollectErrors", true) ;
        $purifier   = new \HTMLPurifier($config);
        $clean_html = $purifier->purify($dirty_html);        
        
        return $clean_html ;
        
    }
    
}
