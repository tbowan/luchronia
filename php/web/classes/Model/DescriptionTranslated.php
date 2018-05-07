<?php

namespace Model ;

trait DescriptionTranslated {
    
    use NameTranslated ;
    
    public function getDescription() {
        $key = static::getDescriptionPrefix() . $this->Name_getName() ;
        return \I18n::translate($key) ;
    }
    
    public static abstract function getDescriptionPrefix() ;
    
}