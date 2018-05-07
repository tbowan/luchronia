<?php

namespace Quantyl\I18n ;

use \Quantyl\Server\Server ;

class IniTranslator implements Translator {
    
    private $_config ;
    private $_translations ;
    private $_lang ;
    
    public function __construct(Server $srv) {
        
        $this->_config = $srv->getConfig() ;
        $this->_lang = $this->_config["I18n.lang"] ;

        $this->_translations = array() ;
    }
    
    private function loadTranslations($lang) {
        $filename = $this->_config["I18n.directory"]
                . DIRECTORY_SEPARATOR 
                . $lang
                . ".php" ;
        
        $this->_translations[$lang] = parse_ini_file($filename) ;
    }
    
    public function translate($key, $params) {
        // Check Lang Loaded
        if (! isset($this->_translation[$this->_lang])) {
            $this->loadTranslations($this->_lang) ;
        }
        $values = $this->_translations[$this->_lang] ;
        // Check translation exists
        if (isset($values[$key])) {
            return @vsprintf($values[$key], $params);
        } else {
            return @vsprintf($key, $params);
        }
    }

}
