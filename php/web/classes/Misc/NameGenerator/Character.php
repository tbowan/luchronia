<?php

namespace Misc\NameGenerator ;

class Character {
    
    public static function generate(\Model\Enums\Race $r, \Model\Enums\Sex $s) {
        
        $racename = ucfirst(strtolower($r->getValue())) ;
        $sexname  = ucfirst(strtolower($s->getValue())) ;
        
        $classname = "\\Misc\\NameGenerator\\" . $racename . $sexname ;
        
        $inst = $classname::Factory() ;
        
        return $inst->generate() ;
    }
    
}
