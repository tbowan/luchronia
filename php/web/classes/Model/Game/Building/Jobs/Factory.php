<?php

namespace Model\Game\Building\Jobs ;

class Factory {

    public static function createFromJob(\Model\Game\Building\Instance $i, \Model\Game\Building\Job $j) {
        $namespace = "\\Model\\Game\\Building\\Jobs\\" ;
        $classname = $namespace . $j->name ;
        $default   = $namespace . "Base" ;
        
        if (class_exists($classname)) {
            return new $classname($i) ;
        } else {
            return new $default($i) ;
        }
    }
    
    public static function createFromInstance(\Model\Game\Building\Instance $i) {
        return self::createFromJob($i, $i->job) ;
    }
    
    
}
