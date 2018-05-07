<?php

namespace Form ;

class I18nTranslation extends \Quantyl\Form\Fields\FullHtml {
    
    public function parse($value) {
        $percent_enc = "%7F" ;
        // 1. escape "%
        $res = str_replace("%", $percent_enc, $value) ;
        // 2. filter html
        $res = parent::parse($res);
        // 3. escape "%" from both previous steps
        $res = str_replace("%", "%%", $res) ;
        $res = str_replace("%$percent_enc", "%", $res) ;
        
        return $res ;
    }
    
}
