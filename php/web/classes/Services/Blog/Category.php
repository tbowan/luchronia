<?php

namespace Services\Blog ;

use Model\Blog\Category as BCategory;
use Quantyl\Form\Form;
use Quantyl\Form\Model\Id;
use Quantyl\Service\EnhancedService;

class Category extends EnhancedService {
    
    public function fillParamForm(Form &$form) {
        $form->addInput("id", new Id(BCategory::getBddTable())) ;
        $form->addInput("page", new \Quantyl\Form\Fields\Integer()) ;
    }

    public function getTitle() {
        return $this->id->name ;
    }
    
    public function getView() {
        $req = $this->getRequest() ;
        $cfg = $req->getServer()->getConfig() ;
        
        return new \Answer\View\Blog\Category($this, $this->id, $this->page, $cfg["blog.postbypage"]) ;
    }
    
    
}
