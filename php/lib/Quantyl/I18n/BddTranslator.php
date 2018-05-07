<?php

namespace Quantyl\I18n ;

use \Quantyl\Server\Server ;

class BddTranslator implements Translator {
    
    private $_statement ;
    private $_lang ;
    
    public function __construct(Server $srv) {
        $this->_lang = \Model\I18n\Lang::GetCurrent($srv) ;
    }
    
    public function translate($key, $params) {
        
        $res = $this->_lang->getTranslation($key) ;
        if ($res === null) {
            return @vsprintf($key, $params);
        } else {
            return @vsprintf($res->translation, $params);
        }
    }

}
