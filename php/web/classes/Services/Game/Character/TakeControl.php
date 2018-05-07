<?php

namespace Services\Game\Character ;

class TakeControl extends \Services\Base\Character {
    
    public function fillParamForm(\Quantyl\Form\Form &$form) {
        $form->addInput("id", new \Quantyl\Form\Model\Id(\Model\Game\Character::getBddTable())) ;
    }
    
    public function checkPermission(\Quantyl\Request\Request $req) {
        parent::checkPermission($req);
        
        if (! $this->getCharacter()->user->equals($this->id->user)) {
            throw new \Quantyl\Exception\Http\ClientForbidden() ;
        }
    }
    
    public function getView() {

        $this->id->previous = $this->id->last ;
        $this->id->last = time() ;
        $this->id->update() ;
        
        $_SESSION["char"] = $this->id ;
        
    }
    
}
