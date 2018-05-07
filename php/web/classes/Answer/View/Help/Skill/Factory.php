<?php

namespace Answer\View\Help\Skill ;

class Factory {
    
    public static function getWidget(\Model\Game\Skill\Skill $skill, $service, $char) {
        
        $name = "\\Answer\\View\\Help\\Skill\\" . $skill->classname ;
        $base = "\\Answer\\View\\Help\\Skill\\" . "Base" ;
        
        if (class_exists($name)) {
            return new $name($service, $skill, $char) ;
        } else {
            return new $base($service, $skill, $char) ;
        }
    }
    
}
