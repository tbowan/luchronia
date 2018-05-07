<?php

namespace Model ;

trait Illustrable {
    
    public function getImage($class = null) {
        return new \Quantyl\XML\Html\Img(
                $this->getImgPath(),
                $this->getName(),
                $class
                ) ;
        
    }
    
    public abstract function getImgPath() ;
    
    public abstract function getName() ;
    
}
