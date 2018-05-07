<?php

namespace Quantyl\Configuration ;

class IniConfiguration extends Configuration {
    
    public function __construct($filename) {
        $values = parse_ini_file($filename, true) ;
        parent::__construct($values);
    }
    
}
