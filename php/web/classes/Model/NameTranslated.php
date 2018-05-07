<?php

namespace Model ;

trait NameTranslated {
    
    use Name {
        Name::getName as Name_getName ;
    }
    
    public function getName() {
        $key = static::getNamePrefix() . $this->Name_getName() ;
        return \I18n::translate($key) ;
    }
    
    public static abstract function getNamePrefix() ;
    
}
