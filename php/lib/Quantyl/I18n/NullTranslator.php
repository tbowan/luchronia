<?php

namespace Quantyl\I18n ;

class NullTranslator implements Translator {
    
    
    public function __construct() {
        ;
    }
    
    public function translate($key, $params) {
        return @vsprintf($key, $params);
    }

}
