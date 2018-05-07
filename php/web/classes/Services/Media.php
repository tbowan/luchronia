<?php

namespace Services ;

use Quantyl\Answer\File;
use Quantyl\Exception\Http\ClientNotFound;
use Quantyl\Form\Fields\Text;
use Quantyl\Form\Form;
use Quantyl\Service\EnhancedService;

class Media extends EnhancedService {
    
    public function fillParamForm(Form &$form) {
        $form->addInput("path", new Text()) ;
    }
    
    public function getView() {
        
        if (strpos($this->path, "/.") !== false || strpos("./", $this->path)) {
            throw new ClientNotFound() ;
        }
        
        $realpath = realpath(BASE_DATA . DIRECTORY_SEPARATOR . $this->path) ;
        
        if (! $realpath) {
            throw new ClientNotFound() ;
        }
        
        return new File($realpath) ;
    }
    
}
