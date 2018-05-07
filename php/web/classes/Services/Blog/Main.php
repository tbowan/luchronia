<?php

namespace Services\Blog ;

use Quantyl\Form\Fields\Integer;
use Quantyl\Form\Form;
use Quantyl\Service\EnhancedService;

class Main extends EnhancedService {
    
    public function fillParamForm(Form &$form) {
        $form->addInput("page", new Integer()) ;
    }

    public function getView() {
        $req = $this->getRequest() ;
        $cfg = $req->getServer()->getConfig() ;
        
        return new \Answer\View\Blog\Main($this, $cfg["blog.postbypage"], $this->page) ;
    }
    
}
