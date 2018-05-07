<?php

namespace Quantyl\Form ;

interface Input {
    
    public function parseValue($value) ;
    
    public function setValue($value) ;
    
    public function isValid() ;
    
    public function getValue() ;
    
    public function getHTML($key = null) ;
    
}
