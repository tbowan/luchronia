<?php

namespace Services\Forum ;

use Model\Forum\Thread as MThread;
use Quantyl\Form\Fields\Boolean;
use Quantyl\Form\Form;
use Quantyl\Form\Model\Id;

class Thread extends \Services\Base\ForumView {
    
    public function fillParamForm(Form &$form) {
        $form->addInput("id",     new Id(MThread::getBddTable())) ;
        $form->addInput("novote", new Boolean()) ;
    }
    
    public function getCategory() {
        return $this->id->category ;
    }
    
    public function getView() {
        $user        = $this->getUser() ;
        $this->id->viewed++ ;
        $this->id->update() ;
        
        return new \Answer\View\Forum\Thread($this, $this->id, $user, $this->novote, $this->getCharacter()) ;
    }
    
    
}
