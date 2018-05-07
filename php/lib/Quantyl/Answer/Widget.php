<?php

namespace Quantyl\Answer ;

abstract class Widget extends Answer {
    
    public function __toString() {
        try {
            return $this->getContent() ;
        } catch (\Exception $e) {
            return  ""
                    . $e->getMessage()
                    . "<p>" . $e->getFile() . " - " . $e->getLine() . "</p>"
                    . "<pre>" . $e->getTraceAsString() . "</pre>"  ;
        }
    }
    
}
